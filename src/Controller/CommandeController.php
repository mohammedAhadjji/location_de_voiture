<?php
namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Users;
use App\Entity\Config;
use App\Entity\Order;
use App\Entity\Reduction;
use App\Entity\Season;
use App\Repository\AnnoncesRepository;
use App\Repository\OrderRepository;
use App\Repository\SittingGeneraleRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    private $or;
    private $doctrine;
    private $sittig;
    private $annonceRepo;
    private $entityManager;

    public function __construct(OrderRepository $or, AnnoncesRepository $an, PersistenceManagerRegistry $doctrine, SittingGeneraleRepository $s, EntityManagerInterface $entityManager)
    {
        $this->or = $or;
        $this->doctrine = $doctrine;
        $this->sittig = $s->find(1);
        $this->annonceRepo = $an;
        $this->entityManager = $entityManager;
    }

    
    #[Route('/request/add', name: 'add_request')]
    public function infoRequest(Request $request, Session $session): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $user_id = $user->getId();
            $baseRedirect = $request->getSchemeAndHttpHost();
            $em = $this->doctrine->getManager();
            $config = $em->getRepository(Config::class)->find(1);
            $clientId = $config->getClientid();
            $newPanier = $this->getLignComand($session);

            $resultat = $this->calculeTotal($session);
            //dd($resultat);
            $Total = $resultat[0] / 100;
            $Sous_total = $resultat[1] /100;
            $Remise = $resultat[2] / 100;
            $ligneCommande = $resultat[3] ;
            $tax = $resultat[4]/100 ;
            $uniqId = uniqid(time(), true);
            $uniqCustomId = $this->generateRandomIdByBytes(4, $uniqId);

            // Vérifiez que $newPanier est bien un tableau et que $newPanier[2] est un objet de type Order
            $order = $newPanier[0] && isset($newPanier[2]) && $newPanier[2] instanceof Order ? $newPanier[2] : new Order();
            $order->setReference($uniqCustomId);
            $clientId = $clientId ?? '';

            $order->setClientId($clientId);
            $order->setUserId($user_id);
            $order->setAmount($Total);
            $order->setLighneOrder($ligneCommande);
            $order->setStatus("0"); // 0 => en cours, 1 => succès, -1 => échec, -2 annulation
            $em->persist($order);
            $em->flush();

            $commandeId = $order->getReference();
            $session->set('order_ref', $commandeId);

            $cancelRedirect = $baseRedirect . '/request/cancel';
            $successRedirect = $baseRedirect . '/request/success';
            $failRedirect = $baseRedirect . '/request/fail';
            $calbackRedirect = $baseRedirect . '/request/callBack';

            return $this->render('commande/summer.html.twig', [
                'sous_total' => $Sous_total,
                'total' => $Total,
                'remise' => $Remise,
                'clientId' => $clientId,
                'commandeId' => $commandeId,
                'cancelRedirect' => $cancelRedirect,
                'successRedirect' => $successRedirect,
                'failRedirect' => $failRedirect,
                'calbackRedirect' => $calbackRedirect,
                'tax' => $tax,
                'sittig' => $this->sittig
            ]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/request/confirmation', name: 'request_sendData')]
    public function sendDataRequest(Request $request, Session $session, PersistenceManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $formParams = $request->request->all();
            $commandeId = $session->get("order_ref");
            $order = $this->or->findOneBy(['reference' => $commandeId]);

            $em = $doctrine->getManager();
            $config = $em->getRepository(Config::class)->find(1);

            $urlConfig = $config->getUrl();
            $storeKey = $config->getStoreKey();

            if ($order && $formParams) {
                $order->setFullname($formParams['BillToName']);
                $order->setCompanyname($formParams['BillToCompany']);
                $order->setAdresse($formParams['BillToStreet1']);
                $order->setPostalCode($formParams['BillToPostalCode']);
                $order->setPays($formParams['BillToCountry']);
                $order->setProvince($formParams['BillToStateProv']);
                $order->setCity($formParams['BillToCity']);
                $order->setPhone($formParams['tel']);
                $order->setEmail($formParams['email']);
                $order->setDate(new DateTime());
                $order->setDateUpdate(new DateTime());
                $em->persist($order);
                $em->flush();
            }

            $postParams = array();
            foreach ($_POST as $key => $value) {
                array_push($postParams, $key);
            }

            natcasesort($postParams);

            $hashval = "";

            foreach ($postParams as $param) {
                $paramValue = trim($_POST[$param]);
                $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

                $lowerParam = strtolower($param);
                if ($lowerParam != "hash" && $lowerParam != "encoding") {
                    $hashval = $hashval . $escapedParamValue . "|";
                }
            }

            $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
            $hashval = $hashval . $escapedStoreKey;

            $calculatedHashValue = hash('sha512', $hashval);
            $hash = base64_encode(pack('H*', $calculatedHashValue));

            return $this->render('commande/request_send_data.html.twig', ['urlConfig' => $urlConfig, 'sittig' => $this->sittig, 'formArray' => $_POST, 'hash' => $hash]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/request/callBack', name: 'callBack_request')]
    public function callBack(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $config = $em->getRepository(Config::class)->find(1);
        $storeKey = $config->getStoreKey();

        $postParams = array();
        foreach ($_POST as $key => $value) {
            array_push($postParams, $key);
        }

        natcasesort($postParams);
        $hach = "";
        $hashval = "";

        foreach ($postParams as $param) {
            $paramValue = html_entity_decode(preg_replace("/\n$/", "", $_POST[$param]), ENT_QUOTES, 'UTF-8');
            $hach = $hach . "(!" . $param . "!:!" . $_POST[$param] . "!)";
            $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

            $lowerParam = strtolower($param);
            if ($lowerParam != "hash" && $lowerParam != "encoding") {
                $hashval = $hashval . $escapedParamValue . "|";
            }
        }

        $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
        $hashval = $hashval . $escapedStoreKey;

        $calculatedHashValue = hash('sha512', $hashval);
        $actualHash = base64_encode(pack('H*', $calculatedHashValue));

        $retrievedHash = $_POST["HASH"];
        $idCommand = $_POST["oid"];
        $ProcReturnCodeVerification = $_POST["ProcReturnCode"];
        $amount = $_POST["amount"];

        $order = $this->or->findOneBy(['reference' => $idCommand]);
        $amountLocal = $order->getAmount();

        if ($retrievedHash == $actualHash) {
            if ($ProcReturnCodeVerification == "00") {
                if ($amount === $amountLocal) {
                    $order->setStatus("1");
                    $order->setSourceUpdate("order success");
                    $order->setSourceUrl("callback url");
                    $order->setDateUpdate(new DateTime());
                    $em->persist($order);
                    $em->flush();
                } else {
                    $order->setStatus("-1");
                    $order->setSourceUpdate("order fail: amount not correct");
                    $order->setSourceUrl("callback url");
                    $order->setDateUpdate(new DateTime());
                    $em->persist($order);
                    $em->flush();
                }
                return new JsonResponse("ok", Response::HTTP_OK);
            } else {
                $order->setStatus("-1");
                $order->setSourceUpdate("order fail: hash not correct");
                $order->setSourceUrl("callback url");
                $order->setDateUpdate(new DateTime());
                $em->persist($order);
                $em->flush();
                return new JsonResponse("ok", Response::HTTP_OK);
            }
        } else {
            $order->setStatus("-1");
            $order->setSourceUpdate("order fail: hash not correct");
            $order->setSourceUrl("callback url");
            $order->setDateUpdate(new DateTime());
            $em->persist($order);
            $em->flush();
            return new JsonResponse("ok", Response::HTTP_OK);
        }

        return new JsonResponse("ok", Response::HTTP_OK);
    }

    #[Route('/request/cancel', name: 'cancel_request')]
    public function cancel(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $session = $request->getSession();

            $em = $doctrine->getManager();
            $commandeId = $session->get("order_ref");
            $order = $this->or->findOneBy(['reference' => $commandeId]);
            $order->setStatus("-2");
            $order->setSourceUpdate("order canceled");
            $order->setSourceUrl("cancel url");
            $order->setDateUpdate(new DateTime());
            $em->persist($order);
            $em->flush();

            $session->set('code', 0);
            return $this->redirectToRoute('show_cart_index', ['code' => "0", 'sittig' => $this->sittig]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/request/success', name: 'success_request')]
    public function success(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $session = $request->getSession();

            $postParams = array();
            foreach ($_POST as $key => $value) { 
                array_push($postParams, $key);
            }

            natcasesort($postParams);

            $hashval = "";
            foreach ($postParams as $param) {
                $paramValue = trim(html_entity_decode($_POST[$param], ENT_QUOTES, 'UTF-8'));
                $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

                $lowerParam = strtolower($param);
                if ($lowerParam != "hash" && $lowerParam != "encoding") {
                    $hashval = $hashval . $escapedParamValue . "|";
                }
            }

            $em = $doctrine->getManager();
            $config = $em->getRepository(Config::class)->find(1);
            $storeKey = $config->getStoreKey();
            $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
            $hashval = $hashval . $escapedStoreKey;

            $calculatedHashValue = hash('sha512', $hashval);
            $actualHash = base64_encode(pack('H*', $calculatedHashValue));

            $retrievedHash = $_POST["HASH"];
            $idCommand = $_POST["oid"];
            $order = $this->or->findOneBy(['reference' => $idCommand]);

            $amount = $_POST["amount"];
            $amountLocal = $order->getAmount();

            if ($retrievedHash == $actualHash) {
                if ($amount === $amountLocal) {
                    $session->set('code', 1);
                    $order->setStatus("1");
                    $order->setSourceUpdate("order success");
                    $order->setSourceUrl("success url");
                    $order->setDateUpdate(new DateTime());
                    $em->persist($order);
                    $em->flush();
                } else {
                    $order->setStatus("-1");
                    $order->setSourceUpdate("order fail: amount not correct");
                    $order->setSourceUrl("success url");
                    $order->setDateUpdate(new DateTime());
                    $em->persist($order);
                    $em->flush();
                }
            } else {
                $responseMessage = "Security Alert. The digital signature is not valid";
                $order->setStatus("-1");
                $order->setSourceUpdate("order fail: hash not correct");
                $order->setSourceUrl("success url");
                $order->setDateUpdate(new DateTime());
                $em->persist($order);
                $em->flush();
            }

            return $this->redirectToRoute('recherche_ancienne', ['code' => "1", 'sittig' => $this->sittig]);
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/request/fail', name: 'fail_request')]
    public function fail(Request $request): Response
    {
        if ($this->getUser()) {
            $session = $request->getSession();

            $postParams = array();
            foreach ($_POST as $key => $value) {
                array_push($postParams, $key);
            }

            natcasesort($postParams);

            $hashval = "";
            foreach ($postParams as $param) {
                $paramValue = trim(html_entity_decode($_POST[$param], ENT_QUOTES, 'UTF-8'));
                $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

                $lowerParam = strtolower($param);
                if ($lowerParam != "hash" && $lowerParam != "encoding") {
                    $hashval = $hashval . $escapedParamValue . "|";
                }
            }

            $em = $doctrine->getManager();
            $config = $em->getRepository(Config::class)->find(1);
            $storeKey = $config->getStoreKey();
            $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
            $hashval = $hashval . $escapedStoreKey;

            $calculatedHashValue = hash('sha512', $hashval);
            $actualHash = base64_encode(pack('H*', $calculatedHashValue));

            $retrievedHash = $_POST["HASH"];
            $idCommand = $_POST["oid"];
            $order = $this->or->findOneBy(['reference' => $idCommand]);
            $session->set('code', -1);

            if ($retrievedHash == $actualHash) {
                $order->setStatus("-1");
                $order->setSourceUpdate("order fail");
                $order->setSourceUrl("fail url");
                $order->setDateUpdate(new DateTime());
                $em->persist($order);
                $em->flush();
            } else {
                $order->setStatus("-1");
                $order->setSourceUpdate("order fail: hash not correct");
                $order->setSourceUrl("fail url");
                $order->setDateUpdate(new DateTime());
                $em->persist($order);
                $em->flush();
            }

            return $this->redirectToRoute('show_cart_index', ['code' => "-1", 'sittig' => $this->sittig]);
        }

        return $this->redirectToRoute('app_login');
    }
    private function calculeTotal($session)
{
    if ($this->getUser()) {
        $panier = $session->get("panier", []);
        $totalAmount = 0; 
        $subtotalAmount = 0; 
        $totalReduction = 0; 
        $content = [];
        $tax = 0; //
        $date = $session->get('date', []);
        $seasonRepository = $this->entityManager->getRepository(Season::class);
        $currentDateTime = new DateTime();

        $currentSeason = $seasonRepository->createQueryBuilder('s')
            ->where(':currentDateTime >= s.date_debut AND :currentDateTime <= s.date_fin')
            ->setParameter('currentDateTime', $currentDateTime)
            ->getQuery()
            ->getOneOrNullResult();

        $taux = $currentSeason ? $currentSeason->getTaux() : 0;

        foreach ($panier as $id => $quantity) {
            $product = $this->annonceRepo->find($id);

            if ($product) {
               
                $reduction = $this->entityManager->getRepository(Reduction::class)->createQueryBuilder('s')
                    ->where(':quantity >= s.min_day AND :quantity <= s.max_day')
                    ->setParameter('quantity', $quantity)
                    ->getQuery()
                    ->getOneOrNullResult();
                
               
                $subtotal = $product->getPrixLocat() * $quantity;
                $subtotalAmount += $subtotal;

                
                $reductionAmount = $reduction ? ($subtotal * $reduction->getReduction()) / 100 : 0;
                $totalReduction += $reductionAmount;

                $totalAfterReduction = $subtotal - $reductionAmount;
                $totalWithTax = $totalAfterReduction + ($totalAfterReduction * $taux / 100);
                $tax += ($totalAfterReduction * $taux / 100);
                $totalAmount += $totalWithTax;
                $content[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'taux' => $taux,
                    'reduction' => $reduction ? $reduction->getReduction() : 0,
                    'date' => $date
                ];
            }
        }
        return [$totalAmount, $subtotalAmount, $totalReduction, $content, $tax];
    }

    return [0, 0, 0, [], 0]; // Si utilisateur non connecte
}


    private function getLignComand($session)
    {
        $user_id = $this->getUser()->getId();
        $or = $this->or->getOrNotTraiter($user_id);
        $panier = $session->get('panier', []);

        if ($or) {
            if (is_object($or) && method_exists($or, 'getLighneOrder')) {
                $ligneCommande = $or->getLighneOrder();

                if ($ligneCommande instanceof ArrayCollection) {
                    foreach ($ligneCommande as $item) {
                        $panier[$item['Reference']] = [
                            'qty' => $item['qty'],
                            'type' => $item['typeSource'],
                        ];
                    }
                }
            }

            return [true, $panier, $or];
        }

        return [false, $panier];
    }

    private function generateRandomIdByBytes($nbrBytes, $uniqId)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $result = "";
        $nbrBytes = max(1, $nbrBytes);
        $length = pow(2, $nbrBytes);

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, 33);
            if ($i == (int)($length / 2)) {
                $result .= $uniqId;
            }
            $result .= substr($str, $index, 1);
        }

        return $result;
    }
}

<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Reduction;
use App\Entity\Season;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\SittingGenerale;
use DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
    }
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, AnnoncesRepository $productsRepository)
    {
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        $panier = $session->get('panier', []);
      //  dd($panier);
        $date =$session->get('date', []);
        $seasonRepository = $this->entityManager->getRepository(Season::class);
        $currentDateTime = new DateTime();


        $currentSeason = $seasonRepository->createQueryBuilder('s')
            ->where(':currentDateTime >= s.date_debut AND :currentDateTime <= s.date_fin')
            ->setParameter('currentDateTime', $currentDateTime)
            ->getQuery()
            ->getOneOrNullResult();
        
        //dd($currentSeason);
        // On initialise des variables
        $data = [];
        $total = 0;
        $taux= 0;
        if($currentSeason){
           $taux= $currentSeason->getTaux();
        }
        

        foreach($panier as $id => $quantity){
            $product = $productsRepository->find($id);
            if ($currentSeason) {
                $taux = $currentSeason->getTaux();
                if ($taux !== null) {
                    $reduction = $this->entityManager->getRepository(Reduction::class);
                    $cu = $reduction->createQueryBuilder('s')
                   ->Where(':quantity >= s.min_day AND :quantity <= s.max_day')
                   ->setParameter('quantity', $quantity) // Assurez-vous de définir $quantity à la valeur souhaitée
                    ->getQuery()
                    ->getOneOrNullResult();
                    

                    $prixLocat =$product->getPrixLocat()-($product->getPrixLocat()*$cu->getReduction()/100);
                    
                    $nouveauPrixLocat = $prixLocat + ($prixLocat * $taux /100);
                    $product->setPrixLocat($nouveauPrixLocat);
                
           
            $red=$cu->getReduction();
            $data[] = [
                'product' => $product,
                'quantity' => $quantity,
                'taux'=> $taux,
                'reduction'=> $red
            ];
            
            $total += $product->getPrixLocat()* $quantity;
        }
        }
        }
        //dd($data);
        return $this->render('cart/index.html.twig', ['data'=>$data, 'total'=>$total,'sittig'=> $sittingGenerale]);
    }


    #[Route('/add/{id}', name: 'add')]
    public function add(Annonces $product, SessionInterface $session)
    {
        
        //On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On ajoute le produit dans le panier s'il n'y est pas encore
        // Sinon on incrémente sa quantité
        if(empty($panier[$id])){
            $panier[$id] = 1;
        }else{
            $panier[$id]++;
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Annonces $product, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        // On retire le produit du panier s'il n'y a qu'1 exemplaire
        // Sinon on décrémente sa quantité
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Annonces $product, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $product->getId();

        // On récupère le panier existant
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');

        return $this->redirectToRoute('cart_index');
    }
}

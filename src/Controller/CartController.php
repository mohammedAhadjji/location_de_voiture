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
    }


    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, AnnoncesRepository $productsRepository): Response
    {
        // Fetch global settings
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        
        // Fetch the cart and date from the session
        $cart = $session->get('panier', []);
        $date = $session->get('date', []);

        // Get the current season based on the current date
        $currentDateTime = new DateTime();
        $seasonRepository = $this->entityManager->getRepository(Season::class);
        $currentSeason = $seasonRepository->createQueryBuilder('s')
            ->where(':currentDateTime >= s.date_debut AND :currentDateTime <= s.date_fin')
            ->setParameter('currentDateTime', $currentDateTime)
            ->getQuery()
            ->getOneOrNullResult();

        // Initialize variables
        $data = [];
        $total = 0;
        $taux = $currentSeason ? $currentSeason->getTaux() : 0;

        // Iterate over cart items
        foreach ($cart as $id => $quantity) {
            $product = $productsRepository->find($id);
            if ($product) {
                if ($currentSeason) {
                    // Fetch reduction based on the quantity
                    $reductionRepository = $this->entityManager->getRepository(Reduction::class);
                    $reduction = $reductionRepository->createQueryBuilder('r')
                        ->where(':quantity >= r.min_day AND :quantity <= r.max_day')
                        ->setParameter('quantity', $quantity)
                        ->getQuery()
                        ->getOneOrNullResult();

                    // Apply reduction and season rate to the product price
                    if ($reduction) {
                        $priceAfterReduction = $product->getPrixLocat() - ($product->getPrixLocat() * $reduction->getReduction() / 100);
                        $newPrice = $priceAfterReduction + ($priceAfterReduction * $taux / 100);
                        $product->setPrixLocat($newPrice);

                        $data[] = [
                            'product' => $product,
                            'quantity' => $quantity,
                            'taux' => $taux,
                            'reduction' => $reduction->getReduction(),
                            'date'=> $date

                        ];
                        $total += $newPrice * $quantity;
                    }
                } else {
                    // If no current season, use the original product price
                    $data[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'taux' => 0,
                        'reduction' => 0,
                        'date'=> $date
                    ];
                    $total += $product->getPrixLocat() * $quantity;
                }
            }
        }

        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total,
            'sittig' => $sittingGenerale,
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Annonces $product, SessionInterface $session): Response
    {
        
        // Fetch the cart from the session
        $cart = $session->get('panier', []);

        // Increment or set the product quantity
        $id = $product->getId();
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('panier', $cart);

        // Redirect to the cart page
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Annonces $product, SessionInterface $session): Response
    {
        // Fetch the cart from the session
        $cart = $session->get('panier', []);
        $id = $product->getId();

        // Decrement or remove the product quantity
        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        $session->set('panier', $cart);

        // Redirect to the cart page
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Annonces $product, SessionInterface $session): Response
    {
        // Fetch the cart from the session
        $cart = $session->get('panier', []);
        $id = $product->getId();

        // Remove the product from the cart
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('panier', $cart);

        // Redirect to the cart page
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session): Response
    {
        // Remove the cart from the session
        $session->remove('panier');

        // Redirect to the cart page
        return $this->redirectToRoute('cart_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\SittingGenerale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PricingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/pricing', name: 'app_pricing')]
    public function index(): Response
    {
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        return $this->render('pricing/index.html.twig', [
            'sittig' => $sittingGenerale,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\FAQ;
use App\Entity\SittingGenerale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FAQController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/f/a/q', name: 'app_f_a_q')]
    public function index(): Response
    {
        $FAQ = $this->entityManager->getRepository(FAQ::class)->findBy([], ['id' => 'ASC']);
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        return $this->render('faq/index.html.twig', [
           'sittig' => $sittingGenerale,
           'faqs' => $FAQ
        ]);
    }
}

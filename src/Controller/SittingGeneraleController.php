<?php

namespace App\Controller;

use App\Entity\SittingGenerale;
use App\Form\SittingGeneraleType;
use App\Repository\SittingGeneraleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sitting/generale')]
class SittingGeneraleController extends AbstractController
{
    #[Route('/', name: 'app_sitting_generale_index', methods: ['GET'])]
    public function index(SittingGeneraleRepository $sittingGeneraleRepository): Response
    {
        return $this->render('sitting_generale/index.html.twig', [
            'sitting_generales' => $sittingGeneraleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sitting_generale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sittingGenerale = new SittingGenerale();
        $form = $this->createForm(SittingGeneraleType::class, $sittingGenerale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sittingGenerale);
            $entityManager->flush();

            return $this->redirectToRoute('app_sitting_generale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sitting_generale/new.html.twig', [
            'sitting_generale' => $sittingGenerale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sitting_generale_show', methods: ['GET'])]
    public function show(SittingGenerale $sittingGenerale): Response
    {
        return $this->render('sitting_generale/show.html.twig', [
            'sitting_generale' => $sittingGenerale,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sitting_generale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SittingGenerale $sittingGenerale, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SittingGeneraleType::class, $sittingGenerale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sitting_generale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sitting_generale/edit.html.twig', [
            'sitting_generale' => $sittingGenerale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sitting_generale_delete', methods: ['POST'])]
    public function delete(Request $request, SittingGenerale $sittingGenerale, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sittingGenerale->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sittingGenerale);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sitting_generale_index', [], Response::HTTP_SEE_OTHER);
    }
}

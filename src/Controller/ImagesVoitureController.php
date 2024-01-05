<?php

namespace App\Controller;

use App\Entity\ImagesVoiture;
use App\Form\ImagesVoitureType;
use App\Repository\ImagesVoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/images/voiture')]
class ImagesVoitureController extends AbstractController
{
    #[Route('/', name: 'app_images_voiture_index', methods: ['GET'])]
    public function index(ImagesVoitureRepository $imagesVoitureRepository): Response
    {
        return $this->render('images_voiture/index.html.twig', [
            'images_voitures' => $imagesVoitureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_images_voiture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $imagesVoiture = new ImagesVoiture();
        $form = $this->createForm(ImagesVoitureType::class, $imagesVoiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($imagesVoiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_images_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_voiture/new.html.twig', [
            'images_voiture' => $imagesVoiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_images_voiture_show', methods: ['GET'])]
    public function show(ImagesVoiture $imagesVoiture): Response
    {
        return $this->render('images_voiture/show.html.twig', [
            'images_voiture' => $imagesVoiture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_images_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImagesVoiture $imagesVoiture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagesVoitureType::class, $imagesVoiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_images_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_voiture/edit.html.twig', [
            'images_voiture' => $imagesVoiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_images_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, ImagesVoiture $imagesVoiture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imagesVoiture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($imagesVoiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_images_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller;

use App\Entity\SittingGenerale;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CantactController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/cantact', name: 'app_cantact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from($data['visitor_email'])
                ->to('mohammed.ahadjji@ump.ac.ma') 
                ->subject('Contact Form Submission')
                ->html($this->renderView('email/contact_email.html.twig', [
                    'name' => $data['visitor_name'],
                    'email' => $data['visitor_email'],
                    'phone' => $data['visitor_phone'],
                    'message' => $data['visitor_message'],
                ]));

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent successfully.');

            return $this->redirectToRoute('app_cantact');
        }
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        return $this->render('cantact/index.html.twig', [
            'sittig' => $sittingGenerale,
            'form' => $form->createView(),
        ]);
    }
}

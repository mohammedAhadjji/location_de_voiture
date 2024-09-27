<?php

namespace App\Controller;

use App\Entity\SittingGenerale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
           

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error 
       , 'sittig'=> $sittingGenerale
    ]);
    }
   /* #[Route(path: '/login/admin', name: 'app_login_admin')]
    public function loginadmin(AuthenticationUtils $authenticationUtils): Response
    {
        // If the user is already authenticated, redirect them to the admin dashboard or another secure page
       /* if ($this->getUser()) {
            return $this->redirectToRoute('admin_'); 
        }*/
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
      //  $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
      /*  $lastUsername = $authenticationUtils->getLastUsername();
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
           

        return $this->render('security/login_admin.html.twig', ['last_username' => $lastUsername, 'error' => $error 
        , 'sittig'=> $sittingGenerale
     ]);
    }*/
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

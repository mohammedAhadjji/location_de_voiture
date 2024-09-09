<?php

namespace App\Controller;

use App\Entity\SittingGenerale;
use App\Entity\Users;
use App\Entity\ImageProfile;
use App\Entity\Order;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/dashbord', name: 'app_user_')]
class UserDashbordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'dashbord')]
    public function index(): Response
    {
        $user=$this->getUser();
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        return $this->render('user_dashbord/index.html.twig', [
            'sittig' => $sittingGenerale,
            'user' => $user,
        ]);
    }

    #[Route('/History', name: 'History')]
    public function History(): Response
    {
        $user=$this->getUser();
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
      //clientId  
      $order = $this->entityManager->getRepository(Order::class)->findBy(['clientId' => $user->getId()]);
     // dd($order);
        return $this->render('user_dashbord/History.html.twig', [
            'sittig' => $sittingGenerale,
            'user' => $user,
            'orders' => $order,
        ]);
    }

    #[Route('/edit/profile', name: 'edit_profile')]
    public function editprofile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash(
               'success',
               'votre profile mise ajour avec sucees'
            );
            return $this->redirectToRoute('app_user_edit_profile');
        }

        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        return $this->render('user_dashbord/edit_profile.html.twig', [
            'sittig' => $sittingGenerale,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/edit/photo/profile', name: 'edit_profile_photo')]
    public function editphotoProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('name_file')->getData();

            $file = $form->get('name_file')->getData();
if ($file && $file->isValid()) {
    $filename = uniqid() . '.' . $file->guessExtension();

    $file->move(
        $this->getParameter('kernel.project_dir') . '/public/uploads/profile_pictures',
        $filename
    );

    $user->setPhoto($filename);
    $this->entityManager->flush();
} else {
    $this->addFlash('error', 'Le fichier n\'est pas valide ou est inaccessible.');
}


            $this->entityManager->flush();

            $this->addFlash('success', 'Votre photo de profil a été mise à jour avec succès.');

            return $this->redirectToRoute('app_user_edit_profile_photo');
        }
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        return $this->render('user_dashbord/edit_profile_photo.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'sittig' => $sittingGenerale,
        ]);
    }
    
    #[Route('/calendra', name: 'calendar')]
    public function calendra(ReservationRepository $Reservation): Response
    {
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        $events = $Reservation->findAll();//client
        $client = $this->getUser();
        $reservations = $Reservation->findByClient($client);

        $rdvs = [];

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay(),
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('user_dashbord/calendar.html.twig', [
            'data' => $data,
            'sittig' => $sittingGenerale
        ]);
    }
    
    #[Route('/edit/password', name: 'edit_password')]
    public function editpass(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $password = $request->request->get('pass');
        $passwordConf = $request->request->get('pass2');
    
        if ($password && $passwordConf) {
            if ($password === $passwordConf) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                
                $this->entityManager->flush();
    
                $this->addFlash('message', 'Mot de passe mis à jour avec succès ✅');
                return $this->redirectToRoute('app_user_edit_password');
            } else {
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        } 
    
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
    
        return $this->render('user_dashbord/editpassword.html.twig', [
            'sittig' => $sittingGenerale,
        ]);
    }
    
}

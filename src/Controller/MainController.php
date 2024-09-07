<?php

namespace App\Controller;

use App\Entity\Type;
use App\Entity\Blogs;
use App\Entity\Brand;
use App\Entity\Order;
use App\Entity\Users;
use App\Entity\Modele;
use App\Entity\Annonces;
use App\Entity\Location;
use App\Form\RapporType;
use App\Entity\Categories;
use App\Form\SendMailType;
use App\Form\AddWishFormType;
use App\Services\MailService;
use App\Entity\SittingGenerale;
use App\Form\SerchAnnoncesType;
use App\Repository\BlogsRepository;
use App\Repository\BrandRepository;
use App\Repository\AnnoncesRepository;
use App\Services\AnnoncesSearchService;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;

class MainController extends AbstractController
{
    private $entityManager;
    private $annoncesSearchService;
    public function __construct(EntityManagerInterface $entityManager,AnnoncesSearchService $annoncesSearchService)
    {
        $this->annoncesSearchService= $annoncesSearchService;
        $this->entityManager = $entityManager;
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $BrandRepository = $this->entityManager->getRepository(Brand::class);
        $modele = $this->entityManager->getRepository(Modele::class)->findBy([], ['id' => 'desc']);
        $LocationRepository = $this->entityManager->getRepository(Location::class);
        $AnoncesRepository = $this->entityManager->getRepository(Annonces::class);
        $nb = $BrandRepository->count([]);
        $nbAN = $AnoncesRepository->count([]);
        $data = $BrandRepository->findBy([], ['id' => 'desc']);
        $dataLocat = $LocationRepository->findBy([], ['id' => 'desc']);
       

        $data1 = $data;
        $dataLocat1 = $dataLocat;
        $ano = $AnoncesRepository->findBy([], ['occur' => 'desc'], 3);

       
        $annonce = $ano;
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);


        return $this->render('main/index.html.twig', [
            'Brands' => $data1,
            'sittig' => $sittingGenerale,
            'annonces' => $annonce,
            'location' => $dataLocat1,
            'modeles'=> $modele
        ]);
    }

    #[Route('/calendra', name: 'app_calendar')]
    public function calendra(ReservationRepository $Reservation): Response
    {
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        $events = $Reservation->findAll();

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
        return $this->render('main/calendra.html.twig', [
            'data' => $data,
            'sittig' => $sittingGenerale
        ]);
    }
    #[Route('/annonces/withlocation/{id}', name: 'annonce_with_location')]
    public function annonces(Location $location)
    {
        // Récupérez toutes les voitures liées à la location
        // Récupérez toutes les annonces liées à la location
        /*$annonces = [];

      foreach ($location->getVoitures() as $voiture) {
          $annoncesDeLaVoiture[] = $voiture->getAnnonces();
          array_merge($annonces, $annoncesDeLaVoiture);
      }*/

        $AnnoncesRepository = $this->entityManager->getRepository(Annonces::class);
        $annonces = $AnnoncesRepository->findBy(['voitureLocale' => $location->getLocale()]);

        // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
            'sittig' => $sittingGenerale,
            'locat' => $location
        ]);
    }
    #[Route('/annonces/withbrand/{id}', name: 'annonce_with_brand')]
    public function annoncesBrand(Brand $brand)
    {
        // Récupérez toutes les voitures liées à la location
        // Récupérez toutes les annonces liées à la location
        /*$annonces = [];

      foreach ($location->getVoitures() as $voiture) {
          $annoncesDeLaVoiture[] = $voiture->getAnnonces();
          array_merge($annonces, $annoncesDeLaVoiture);
      }*/

        $AnnoncesRepository = $this->entityManager->getRepository(Annonces::class);
        //  $annonces = $AnnoncesRepository->findBy(['brandVoiture' => $brand->getName()]);
        $annonces = $AnnoncesRepository->findBy(['brandVoiture' => $brand->getName()], ['occur' => 'ASC']);

        // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('annonces/indexBrand.html.twig', [
            'annonces' => $annonces,
            'sittig' => $sittingGenerale,
            'brand' => $brand
        ]);
    }
    #[Route('/annonces/detail/{id}', name: 'annonce_detail')]
    public function annoncesdetail(
        Annonces $annonce,
        EntityManagerInterface $entityManager,
        Request $request,
        SessionInterface $session,
        MailService $mailService
    ) {
        $form = $this->createForm(AddWishFormType::class);
        $form->handleRequest($request);

        $sendMailForm = $this->createForm(SendMailType::class);
        $sendMailForm->handleRequest($request);
        $ReportForm = $this->createForm(RapporType::class);
        $ReportForm->handleRequest($request);

        if ($ReportForm->isSubmitted() && $ReportForm->isValid()) {
            $donneesFormulaire = $ReportForm->getData();

            // Assurez-vous que les clés correspondent à celles du formulaire
            $from = $donneesFormulaire['email'] ?? "";
            $to = "mohammed.ahadjji@ump.ac.ma";
            $subject = 'Rapport de ' . $annonce->__toString();
            $message = $donneesFormulaire['name'] . '<br/>' .
                $donneesFormulaire['email'] . '<br/>' .
                $donneesFormulaire['phone'] . '<br/>' .
                "<div style='padding:5%;'>" . $donneesFormulaire['message'] . '<br/></div>';

            $mailService->sendMail($from, $to, $subject, $donneesFormulaire);
            $this->addFlash('success', 'Votre Report a bien été envoyé');
        }
        // dd($sendMailForm->getData());
        if ($sendMailForm->isSubmitted() && $sendMailForm->isValid()) {
            $donneesFormulaire = $sendMailForm->getData();

            // Assurez-vous que les clés correspondent à celles du formulaire
            $from = $donneesFormulaire['email'] ?? "";
            $to = "mohammed.ahadjji@ump.ac.ma";
            $subject = 'Rapport de ' . $annonce->__toString();
            $message = $donneesFormulaire['name'] . '<br/>' .
                $donneesFormulaire['email'] . '<br/>' .
                $donneesFormulaire['phone'] . '<br/>' .
                "<div style='padding:5%;'>" . $donneesFormulaire['message'] . '<br/></div>';

            $mailService->sendMail($from, $to, $subject, $donneesFormulaire);
            $this->addFlash('success', 'Votre email a bien été envoyé');
        }//debut 
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            if (!$user) {
                // Si l'utilisateur n'est pas connecté, redirigez vers la page de connexion
                return $this->redirectToRoute('app_login');
            }

            $donneesFormulaire = [
                'res' => $form->get('reservationDate')->getData(),
                'day' => $form->get('numberOfDays')->getData(),
            ];

            $reservationDate = $donneesFormulaire['res'];
            $numberOfDays = $donneesFormulaire['day'];

            if (!$reservationDate instanceof \DateTime) {
                $reservationDate = new \DateTime($reservationDate);
            }

            // Calcul de la date de fin
            $interval = new \DateInterval('P' . $numberOfDays . 'D');
            $endDate = (clone $reservationDate)->add($interval);

            // Ajoutez la réservation au panier
            $id = $annonce->getId();
            $panier = $session->get('panier', []);

            if (empty($panier[$id])) {
                $panier[$id] = $numberOfDays;
            } else {
                $panier[$id] = $numberOfDays;
            }

            $session->set('date', $reservationDate);
            $session->set('panier', $panier);

            // Redirigez vers la page du panier
            return $this->redirectToRoute('cart_index');
        }

        $annonce->setOccur($annonce->getOccur() + 1);
        $entityManager->persist($annonce);
        $entityManager->flush();

        $BrandRepository = $entityManager->getRepository(Brand::class);
        $LocationRepository = $entityManager->getRepository(Location::class);
        $dataBrand = $BrandRepository->findBy([], ['id' => 'desc']);
        $dataLocat = $LocationRepository->findBy([], ['id' => 'desc']);

        // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
        $sittingGenerale = $entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('annonces/show.html.twig', [
            'annonce' => $annonce,
            'sittig' => $sittingGenerale,
            'brands' => $dataBrand,
            'form' => $form->createView(),
            'email' => $sendMailForm->createView(),
            'ReportForm' => $ReportForm->createView(),
            'location' => $dataLocat,
        ]);
    }

    #[Route('/brands', name: 'brand_listing')]
    public function brandshow()
    {
        // Récupérez toutes les voitures liées à la location
        // Récupérez toutes les annonces liées à la location
        /*$annonces = [];

      foreach ($location->getVoitures() as $voiture) {
          $annoncesDeLaVoiture[] = $voiture->getAnnonces();
          array_merge($annonces, $annoncesDeLaVoiture);
      }*/



        $BrandRepository = $this->entityManager->getRepository(Brand::class);

        $dataBrand = $BrandRepository->findBy([], ['id' => 'desc']);

        // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('main/showbrand.html.twig', [

            'sittig' => $sittingGenerale,
            'brands' => $dataBrand

        ]);
    }
    #[Route('/locations', name: 'location_listing')]
    public function locationshow()
    {
        // Récupérez toutes les voitures liées à la location
        // Récupérez toutes les annonces liées à la location
        /*$annonces = [];

      foreach ($location->getVoitures() as $voiture) {
          $annoncesDeLaVoiture[] = $voiture->getAnnonces();
          array_merge($annonces, $annoncesDeLaVoiture);
      }*/



        $locationRepository = $this->entityManager->getRepository(Location::class);

        $data = $locationRepository->findBy([], ['id' => 'desc']);
        $annoncesRepository = $this->entityManager->getRepository(Annonces::class);
        $annonces = $annoncesRepository->findBy([], ['occur' => 'ASC']);
        // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('main/showlocation.html.twig', [

            'sittig' => $sittingGenerale,
            'location' => $data,
            'annonces' => $annonces,

        ]);
    }
    #[Route('/liste/annonces', name: 'liste_annonces')]
    public function annoces(AnnoncesRepository $AnnoncesRepository, Request $request ,PaginatorInterface $paginator): Response
    {
        // Récupération des paramètres de la requête
        $mots = $request->request->get('text');
        $type = $request->request->get('type');
        $brand = $request->request->get('brand');
        $model = $request->request->get('model');
        $location = $request->request->get('location');
        $page = $request->request->getInt('page', 1);
        $limit = $request->request->getInt('limit',10 );

        // Vérifier si des paramètres de filtrage sont présents dans la requête
        if ($mots || $type || $brand || $model || $location) {
            // Effectuer la recherche avec les paramètres
            $annonces = $this->annoncesSearchService->searchAnnonces($mots, $type, $brand, $model, $location,$page = $request->query->getInt('page', 1), $limit);
            $data = $annonces;  // No pagination for filtered results
        } else {
            $page = $request->query->getInt('page', 1);  // Default to page 1 if no page is set
            $limit = $request->request->getInt('limit',10 );
            
            // Afficher le contenu par défaut si aucun paramètre de filtrage
            $data = $paginator->paginate( $AnnoncesRepository->findBy([], ['occur' => 'DESC']),$page,$limit);
           
        }
        
        $types = $this->entityManager->getRepository(Type::class)->findBy([], ['id' => 'DESC']);
        $locationListing = $this->entityManager->getRepository(Location::class)->findall();
        $Brandslisting = $this->entityManager->getRepository(Brand::class)->findBy([], ['id' => 'ASC']);
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
/*dd($Brandslisting[0]->getLogos()); $mots = $request->request->get('text');
        $type = $request->request->get('type');
        $brand = $request->request->get('brand');
        $model = $request->request->get('model');
        $location*/
        return $this->render('main/annonce.html.twig', [
            'Annonces' => $data,
            'types' => $types,
            'brandslisting' => $Brandslisting,
            'locationListing' => $locationListing,
            'sittig' => $sittingGenerale,
            'type' => $type,
            'brand' => $brand,
           'model' => $model,
           'location'=>$location,
           'mots' => $mots,
            'page' => $page,
            'limit' => $limit,
            'total' => ceil($data->getTotalItemCount() / $limit) // Total pages

        ]);
    }
    #[Route('/liste/blogs', name: 'liste_blogs')]
    public function listbloges(BlogsRepository $blogsRepo): Response
    {

        $data = $blogsRepo->findBy(['active' => true], ['id' => 'ASC']);

        if (isset($data[7])) {
            // Remove the 8th element (array index is 7 because it's 0-based)
            unset($data[7]);

            // Re-index the array to fill the gap
            $data = array_values($data);
        }

        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('blogs/blogs.html.twig', [
            'blogs' => $data,
            'sittig' => $sittingGenerale
        ]);
    }
    #[Route('/liste/blogs/detail/{id}', name: 'blogs_detail')]
    public function blogedetails(Blogs $blog): Response
    {

        $data = $blog;
        $categorie = $this->entityManager->getRepository(Categories::class)->findBy([], ['id' => 'desc']);

        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('blogs/blog_detail.html.twig', [
            'blog' => $data,
            'sittig' => $sittingGenerale,
            'categories' => $categorie
        ]);
    }
    #[Route('/blogs/withcategorie/{id}', name: 'blogs_with_categorie')]
    public function blogswithCate(Categories $categorie)
    {
        // Récupérez toutes les voitures liées................ à la location
        // Récupérez toutes ...............................................................s annonces liées à la location
        /*$annonces = [];

  foreach ($location->getVoitures() as $voiture) {
      $annoncesDeLaVoiture[] = $voiture->getAnnonces();
      array_merge($annonces, $annoncesDeLaVoiture);
  }*/

        $blogRepository = $this->entityManager->getRepository(Blogs::class);
        //  $annonces = $AnnoncesRepository->findBy(['brandVoiture' => $brand->getName()]);
        $blogs = $blogRepository->findBy(['categorie' => $categorie], ['id' => 'ASC']);

        // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('blogs/blogs_with_categories.html.twig', [
            'blogs' => $blogs,
            'sittig' => $sittingGenerale,
            'categorie' => $categorie
        ]);
    }
}

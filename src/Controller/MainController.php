<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Blogs;
use App\Entity\Brand;
use App\Entity\Categories;
use App\Entity\Location;
use App\Entity\SittingGenerale;
use App\Form\AddWishFormType;
use App\Form\SerchAnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Repository\BlogsRepository;
use App\Repository\BrandRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityPaginatorInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class MainController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
        $BrandRepository = $this->entityManager->getRepository(Brand::class);
        $LocationRepository = $this->entityManager->getRepository(Location::class);
        $AnoncesRepository = $this->entityManager->getRepository(Annonces::class);
        $nb = $BrandRepository->count([]);
        $nbAN = $AnoncesRepository->count([]);
        $data= $BrandRepository->findBy([],['id' => 'desc']);
        $dataLocat = $LocationRepository->findBy([],['id'=> 'desc']);
        //boucle Brand
            /*if ($nb>8) {
                
                for($i=0;$i<8;$i++){
                    $data1[$i]=$data[$i];
                    $dataLocat1[$i]=$dataLocat[$i];
                  }
            } else {
                
                $dataLocat1=$dataLocat;
                $data1=$data;
            }*/

            $data1=$data;
            $dataLocat1=$dataLocat;
            //boucle d'annonces
            $ano= $AnoncesRepository->findBy([],['occur' => 'desc']);
            /*if ($nb>8) {
                for($i=0;$i<8;$i++){
                    $annonce[$i]=$ano[$i];
                  }
            } else {
                
                $annonce=$ano;
            }*/
            $annonce=$ano;
            $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
           
        
        return $this->render('main/index.html.twig', [
            'Brands' => $data1,
            'sittig'=> $sittingGenerale,
            'annonces'=> $annonce,
            'location'=> $dataLocat1
        ]);
    }
    
    #[Route('/calendra', name: 'app_calendar')]
    public function calendra(ReservationRepository $Reservation): Response
    {
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
        $events = $Reservation->findAll();

        $rdvs = [];

        foreach($events as $event){
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
        return $this->render('main/calendra.html.twig',[
            'data' => $data,
            'sittig'=> $sittingGenerale
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
    $annonces = $AnnoncesRepository->findBy(['brandVoiture' => $brand->getName()],['occur' => 'ASC']);

    // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
    $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

    return $this->render('annonces/indexBrand.html.twig', [
        'annonces' => $annonces,
        'sittig' => $sittingGenerale,
        'brand' => $brand
    ]);
    }
    #[Route('/annonces/detail/{id}', name: 'annonce_detail')]
    public function annoncesdetail(Annonces $annonce,EntityManagerInterface $entityManager,Request $request, SessionInterface $session)
    {
         // Récupérez toutes les voitures liées à la location
      // Récupérez toutes les annonces liées à la location
      /*$annonces = [];

      foreach ($location->getVoitures() as $voiture) {
          $annoncesDeLaVoiture[] = $voiture->getAnnonces();
          array_merge($annonces, $annoncesDeLaVoiture);
      }*/
      $form = $this->createForm(AddWishFormType::class);
      $search = $form->handleRequest($request);
      
     $id = $annonce->getId();
      
      if ($form->isSubmitted() ) {
        // On recherche les annonces correspondant aux mots clés
        $donneesFormulaire = [
            'res' => $search->get('reservationDate')->getData(),
            'day' => $search->get('numberOfDays')->getData(),
        ];
        $panier = $session->get('panier', []);

        // On ajoute le produit dans le panier s'il n'y est pas encore
        // Sinon on incrémente sa quantité
        if(empty($panier[$id])){
            $panier[$id] = $donneesFormulaire['day'];
        }else{
            $panier[$id] = $donneesFormulaire['day'];
        }
        $date = $donneesFormulaire['res'];
        
        $session->set('date', $date);
        $session->set('panier', $panier);
        
        //On redirige vers la page du panier
        return $this->redirectToRoute('cart_index');
    
           }
      
     
    $annonces = $annonce;
    $i=$annonces->getOccur();
    $annonces->setOccur($annonces->getOccur()+1);
    $entityManager->persist($annonce); // Persistez l'objet modifié en utilisant l'EntityManager (assurez-vous d'importer EntityManager si nécessaire)
$entityManager->flush();


    $BrandRepository = $this->entityManager->getRepository(Brand::class);
        $LocationRepository = $this->entityManager->getRepository(Location::class);
        $dataBrand = $BrandRepository->findBy([],['id'=> 'desc']);
        $dataLocat = $LocationRepository->findBy([],['id'=> 'desc']);
    // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
    $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

    return $this->render('annonces/show.html.twig', [
        'annonce' => $annonces,
        'sittig' => $sittingGenerale,
        'brands'=> $dataBrand,
        'form'=> $form->createView(),
        'location'=> $dataLocat
        
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
       
        $dataBrand = $BrandRepository->findBy([],['id'=> 'desc']);
      
    // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
    $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

    return $this->render('main/showbrand.html.twig', [
       
        'sittig' => $sittingGenerale,
        'brands'=> $dataBrand
        
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
       
        $data = $locationRepository->findBy([],['id'=> 'desc']);
    $annoncesRepository= $this->entityManager->getRepository(Annonces::class);
        $annonces = $annoncesRepository->findBy([],['occur' => 'ASC'] );
    // Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
    $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

    return $this->render('main/showlocation.html.twig', [
       
        'sittig' => $sittingGenerale,
        'location'=> $data,'annonces' => $annonces,
        
    ]);
    }
    #[Route('/liste/annonces', name: 'liste_annonces')]
    public function annoces(AnnoncesRepository $AnnoncesRepository, Request $request): Response
    {   

        $data = $AnnoncesRepository->findBy([], ['occur' => 'DESC'] );
        $form = $this->createForm(SerchAnnoncesType::class);
        $search = $form->handleRequest($request);  
 
        if($form->isSubmitted() && $form->isValid()){
           
            $data = $AnnoncesRepository->search(
                $search->get('mots')->getData(),
                $search->get('type')->getData()
            );

        }
        
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('main/annonce.html.twig', [
            'Annonces'=>$data,
            'form' => $form->createView(),
            'sittig'=>$sittingGenerale
    ]);
    }
    #[Route('/liste/blogs', name: 'liste_blogs')]
    public function listbloges(BlogsRepository $blogsRepo): Response
    {   

        $data = $blogsRepo->findBy(['active' => true], ['id' => 'ASC'] );
       
        if (isset($data[7])) {
            // Remove the 8th element (array index is 7 because it's 0-based)
            unset($data[7]);
            
            // Re-index the array to fill the gap
            $data = array_values($data);
        }
        
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('blogs/blogs.html.twig', [
            'blogs'=>$data,
            'sittig'=>$sittingGenerale
    ]);
    }
    #[Route('/liste/blogs/detail/{id}', name: 'blogs_detail')]
    public function blogedetails(Blogs $blog): Response
    {   

        $data = $blog;
        $categorie = $this->entityManager->getRepository(Categories::class)->findBy([],['id' => 'desc']);
        
        $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

        return $this->render('blogs/blog_detail.html.twig', [
            'blog'=>$data,
            'sittig'=>$sittingGenerale,
            'categories'=>$categorie
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
$blogs = $blogRepository->findBy(['categorie' => $categorie],['id' => 'ASC']);

// Récupérez la SittingGenerale (assurez-vous que l'id 1 existe dans votre base de données)
$sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);

return $this->render('blogs/blogs_with_categories.html.twig', [
    'blogs' => $blogs,
    'sittig' => $sittingGenerale,
    'categorie' => $categorie
]);
}
}
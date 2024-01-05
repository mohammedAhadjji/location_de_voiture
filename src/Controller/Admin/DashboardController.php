<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Entity\Blogs;
use App\Entity\Brand;
use App\Entity\Categories;
use App\Entity\Config;
use App\Entity\ImagesBlogs;
use App\Entity\ImagesVoiture;
use App\Entity\Location;
use App\Entity\Modele;
use App\Entity\Redection;
use App\Entity\Reduction;
use App\Entity\Reservation;
use App\Entity\Season;
use App\Entity\SittingGenerale;
use App\Entity\Type;
use App\Entity\Users;
use App\Entity\Voiture;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use function PHPSTORM_META\type;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin_')]
    public function index(): Response
    {
        //return parent::index();
        // Check if the SittingGenerale record with entity ID 1 exists
    $sittingGenerale = $this->entityManager->getRepository(SittingGenerale::class)->find(1);
    $config = $this->entityManager->getRepository(Config::class)->find(1);

    if (!$sittingGenerale) {
        // If the record doesn't exist, create a new one
        $config = new Config();
        // Set default values or any necessary data
         // Assuming 'name' is a property of SittingGenerale

        // Persist and flush the newly created record
        $this->entityManager->persist($sittingGenerale);
        $this->entityManager->flush();
    }


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }
        //$userCount = $this->getDoctrine()->getRepository(::class)->count([]);
        $userCount =  $this->entityManager->getRepository(Users::class)->count([]);
        $annonceCount =  $this->entityManager->getRepository(Annonces::class)->count([]);
        $userRepository = $this->entityManager->getRepository(Users::class);
        $usa = $userRepository->count(['isVerified' => 1]);
        $usina = $userRepository->count(['isVerified' => 0]);

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userCount,
            'annonceCount' => $annonceCount,
            'activeUser' => $usa,
            'inactive' => $usina
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            //->setTitle('Location de Voitures');<img src="https://demo.phpscriptpoint.com/carpoint/uploads/site_photos/a7eb55f8e8c41cbaee0760d15b226f4d.png" alt=""><small>Location de Voiture</small>
            ->setTitle('<img src="https://demo.phpscriptpoint.com/carpoint/uploads/site_photos/a7eb55f8e8c41cbaee0760d15b226f4d.png" style="height:50px;width:auto"> <br>  <span class="text-small"></span>');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        //yield MenuItem::linkToCrud('les ', 'fas fa-list', ::class);
        yield MenuItem::section('sittings');
        
        yield MenuItem::subMenu('Sitting Generale', 'fas fas fa-folder')->setSubItems([

            // MenuItem::linkToCrud('Update Settings', 'fas fa-cog', SettingGenerale::class)->setAction(Crud::PAGE_EDIT),
            MenuItem::linkToCrud('Show Main Settings', 'fa fa-tags', SittingGenerale::class)
                ->setAction('detail')
                ->setEntityId(1),
            MenuItem::linkToCrud('Edit Main Settings', 'fa fa-pencil', SittingGenerale::class)
                ->setAction('edit')
                ->setEntityId(1),
          // MenuItem::linkToCrud('Sitting Generale', 'fas fa-plus', SittingGenerale::class)->setAction(Crud::PAGE_NEW),
        //MenuItem::linkToCrud('Sitting Generale', 'fas fa-bars',SittingGenerale::class)
        ]);
        yield MenuItem::section('config payment');
        
        yield MenuItem::subMenu('config ', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create season', 'fas fa-plus', Config::class)->setAction(crud::PAGE_NEW),
            // MenuItem::linkToCrud('Update Settings', 'fas fa-cog', SettingGenerale::class)->setAction(Crud::PAGE_EDIT),
            MenuItem::linkToCrud('Show Main Settings', 'fa fa-tags', Config::class)
                ->setAction('detail')
                ->setEntityId(1),
            MenuItem::linkToCrud('Edit Main Settings', 'fa fa-pencil', Config::class)
                ->setAction('edit')
                ->setEntityId(1),
          // MenuItem::linkToCrud('Sitting Generale', 'fas fa-plus', SittingGenerale::class)->setAction(Crud::PAGE_NEW),
        //MenuItem::linkToCrud('Sitting Generale', 'fas fa-bars',SittingGenerale::class)
        ]);
        yield MenuItem::section('Users');
        yield MenuItem::subMenu('Users', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create user', 'fas fa-plus', Users::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show users', 'fas fa-bars', Users::class)
        ]);
        yield MenuItem::section('configurations');
        yield MenuItem::subMenu('images', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Show images voiture', 'fas fa-bars', ImagesVoiture::class),
            MenuItem::linkToCrud('Show images blogs', 'fas fa-bars', ImagesBlogs::class)

        ]);
        yield MenuItem::subMenu('season', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create season', 'fas fa-plus', Season::class)->setAction(crud::PAGE_NEW),
            MenuItem::linkToCrud('Show season', 'fas fa-bars', season::class)

        ]);
        yield MenuItem::subMenu('Rerduction', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create Rerduction', 'fas fa-plus', Reduction::class)->setAction(crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Rerduction', 'fas fa-bars', Reduction::class)

        ]);
        yield MenuItem::subMenu('types', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create types', 'fas fa-plus', Type::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show types', 'fas fa-bars', Type::class)
        ]);
        yield MenuItem::subMenu('Categories', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create Categories', 'fas fa-plus', Categories::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Categories', 'fas fa-bars', Categories::class)
        ]);
        yield MenuItem::subMenu('location', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create location', 'fas fa-plus', Location::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show location', 'fas fa-bars', Location::class)
        ]);
        yield MenuItem::subMenu('modele', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create modele', 'fas fa-plus', Modele::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show modele', 'fas fa-bars', Modele::class)
        ]);
        yield MenuItem::subMenu('brand', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create brand', 'fas fa-plus',Brand::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show brand', 'fas fa-bars', Brand::class)
        ]);
        yield MenuItem::section('listing');
        yield MenuItem::subMenu('les Voiture', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create Voiture', 'fas fa-plus', Voiture::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show voiture', 'fas fa-bars', Voiture::class)

        ]);
        yield MenuItem::subMenu('les Réservation', 'fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create Reservation', 'fas fa-plus', Reservation::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Reservation', 'fas fa-bars', Reservation::class)
        ]);
        yield MenuItem::subMenu('les Annonces', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create Annonce', 'fas fa-plus', Annonces::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Annonces', 'fas fa-bars', Annonces::class),

        ]);
        yield MenuItem::subMenu('les Blogs', 'fas fas fa-folder')->setSubItems([
            MenuItem::linkToCrud('Create Blogs', 'fas fa-plus', Blogs::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Blogs', 'fas fa-bars', Blogs::class),

        ]);
        yield MenuItem::section('Logout');
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }
}

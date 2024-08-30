<?php
namespace App\Controller\ajax;

use App\Entity\Annonces;
use App\Entity\Brand;
use App\Entity\Location;
use App\Entity\Modele;
use App\Entity\Type;
use App\Repository\AnnoncesRepository;
use App\Services\AnnoncesSearchService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxController extends AbstractController
{
    private $entityManager;
    private $annoncesSearchService;
    private $annoncesRepository;
    private $serializer;

  
    public function __construct(AnnoncesRepository $annoncesRepository,AnnoncesSearchService $annoncesSearchService ,EntityManagerInterface $entityManager,SerializerInterface $serializer)
    {
        $this->annoncesSearchService = $annoncesSearchService;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->annoncesRepository = $annoncesRepository;
    }
    #[Route('/ajax/location/{id}/brands', name: 'ajax_brands')]
    public function getBrandByLocations(Location $location): JsonResponse
    {
       // $brands = $this->entityManager->getRepository(Brand::class)->findAll();
        $brands = $location->getBrands();
        $data = [];

        foreach ($brands as $brand) {
            $data[] = [
                'id' => $brand->getId(),
                'name' => $brand->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/ajax/brand/{id}/models', name: 'ajax_models')]
    public function getModels(Brand $brand): JsonResponse
    {
       
        $models = $brand->getModeles();
        $data = [];

        foreach ($models as $model) {
            $data[] = [
                'id' => $model->getId(),
                'name' => $model->getName(),
            ];
        }

        return new JsonResponse($data);
    }
   /**
     * @Route("/api/annonces/search", name="api_annonces_search", methods={"GET"})
     */
    public function search(Request $request): JsonResponse
    {
        $mots = $request->query->get('mots');
        $type = $request->query->get('type');
        $brand = $request->query->get('brand');
        $modele = $request->query->get('modele');
        $location = $request->query->get('location');

        $annonces = $this->annoncesSearchService->searchAnnonces($mots, $type, $brand, $modele, $location);

        // Sérialisation des données en JSON
        $jsonContent = $this->serializer->serialize($annonces, 'json', ['groups' => 'annonces']);

        return new JsonResponse($jsonContent, 200, [], true);
    }
    

}


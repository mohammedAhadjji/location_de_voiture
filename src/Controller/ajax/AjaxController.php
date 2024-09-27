<?php
namespace App\Controller\ajax;

use App\Entity\Annonces;
use App\Entity\Brand;
use App\Entity\Location;
use App\Entity\Modele;
use App\Entity\Type;
use App\Repository\AnnoncesRepository;
use App\Repository\CommentsRepository;
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
        $page = $request->query->get('page');

        $annonces = $this->annoncesSearchService->searchAnnonces($mots, $type, $brand, $modele, $location,$page);

        $jsonContent = $this->serializer->serialize($annonces, 'json', ['groups' => 'annonces']);

        return new JsonResponse($jsonContent, 200, [], true);
    }
    
    #[Route('/comments/load', name: 'load_comments')]
    public function loadComments(Request $request, CommentsRepository $commentRepository): JsonResponse
    {
        $page = (int) $request->query->get('page', 1); 
        $limit = (int) $request->query->get('limit', 4); 
    
        $comments = $commentRepository->findBy(
            ['isVerify' => false], // Critère de filtre pour récupérer seulement les commentaires non vérifiés
            ['id' => 'DESC'], // Ordre par ID décroissant
            $limit, // Limite de résultats
            ($page - 1) * $limit // Calcul de l'offset pour la pagination
        );  
        $totalComments = count($commentRepository->findAll());
        $totalPages = ceil($totalComments / $limit);
    
        $data = [];
        foreach ($comments as $comment) {
            $data[] = [
                'id' => $comment->getId(),
                'content' => $comment->getContenu(),
                'isVerify' => $comment->isIsVerify(),
            ];
        }
    
        return new JsonResponse([
            'comments' => $data,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
    }
    
    

#[Route('/comments/{id}/verify', name: 'toggle_verify', methods: ['POST'])]
public function toggleVerify(int $id, CommentsRepository $commentRepository, EntityManagerInterface $em): JsonResponse
{
    $comment = $commentRepository->find($id);
    if (!$comment) {
        return new JsonResponse(['error' => 'Comment not found'], 404);
    }

    $comment->setIsVerify(!$comment->isIsVerify());
    $em->flush();

    return new JsonResponse(['success' => true, 'newStatus' => $comment->isIsVerify()]);
}
#[Route('/comments/{id}/delete', name: 'delete_comment', methods: ['DELETE'])]
public function deleteComment(int $id, CommentsRepository $commentRepository, EntityManagerInterface $em): JsonResponse
{
    $comment = $commentRepository->find($id);
    if (!$comment) {
        return new JsonResponse(['error' => 'Comment not found'], 404);
    }

    $em->remove($comment);
    $em->flush();

    return new JsonResponse(['success' => true]);
}

}


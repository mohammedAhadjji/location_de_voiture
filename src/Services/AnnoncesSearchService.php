<?php
namespace App\Services;

use App\Repository\AnnoncesRepository;
use Knp\Component\Pager\PaginatorInterface;

class AnnoncesSearchService
{
    private $annoncesRepository;
    private $paginator;

    public function __construct(AnnoncesRepository $annoncesRepository, PaginatorInterface $paginator)
    {
        $this->annoncesRepository = $annoncesRepository;
        $this->paginator = $paginator;
    }

    /**
     * Search annonces based on filters and paginate the results
     * 
     * @param string|null $mots
     * @param int|null $type
     * @param int|null $brand
     * @param int|null $modele
     * @param int|null $location
     * @param int $page
     * @param int $limit
     * 
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchAnnonces($mots = null, $type = null, $brand = null, $modele = null, $location = null, $page = 1, $limit = 10)
    {
        
        // Fetch annonces from the repository based on filters
        $query = [];
        $query = $this->annoncesRepository->search($mots, $type, $brand, $modele, $location);
        
        // Paginate the results
        return $this->paginator->paginate($query, $page, $limit);
    }
}

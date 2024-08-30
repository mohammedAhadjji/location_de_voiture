<?php
namespace App\Services;

use App\Repository\AnnoncesRepository;

class AnnoncesSearchService
{
    private $annoncesRepository;

    public function __construct(AnnoncesRepository $annoncesRepository)
    {
        $this->annoncesRepository = $annoncesRepository;
    }

    public function searchAnnonces($mots = null, $type = null, $brand = null, $modele = null, $location = null)
    {
        return $this->annoncesRepository->search($mots, $type, $brand, $modele, $location);
    }
}

<?php
namespace App\services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Season;

class SeasonalPricingService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAdjustedRentalPrice($basePrice, $reservationDate)
    {
        // Retrieve the season based on the reservation date
        $seasonEntity = $this->getSeasonByReservationDate($reservationDate);

        if ($seasonEntity) {
            $seasonRate = $seasonEntity->getTaux();

            // Calculate the adjusted price based on the season rate
            $adjustedPrice = $basePrice * (1 + $seasonRate / 100);

            return $adjustedPrice;
        } else {
            // Season not found, use the base price
            return $basePrice;
        }
    }

    private function getSeasonByReservationDate($reservationDate)
    {
        // Retrieve the season based on the reservation date
        $season = $this->entityManager->getRepository(Season::class)->findOneBy([
            'date_debut' => ['<=', $reservationDate],
            'date_fin' => ['>=', $reservationDate],
        ]);

        return $season;
    }
}

<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
#[ORM\Index(name: 'annonces_ids', columns: ['title', 'descriptions'], flags: ['fulltext'])]
class Annonces
{
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->occur = 0;
        
    }
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['annonces'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['annonces'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,nullable: true)]
    #[Groups(['annonces'])]
    public ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['annonces'])]
    private ?string $descriptions = null;

    #[ORM\Column]
    #[Groups(['annonces'])]
    public ?int $prix_locat = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[Groups(['annonces'])]
    public ?Voiture $voiture = null;

    #[ORM\Column(length: 255)]
    #[Groups(['annonces'])]
    private ?string $voitureLocale = null;

    #[ORM\Column(length: 255)]
    #[Groups(['annonces'])]
    private ?string $brandVoiture = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['annonces'])]
    private ?int $occur = null;

    #[ORM\ManyToOne(inversedBy: 'Annonces')]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDescriptions(): ?string
    {
        return $this->descriptions;
    }

    public function setDescriptions(?string $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    public function getPrixLocat(): ?int
    {
        return $this->prix_locat;
    }

    public function setPrixLocat(int $prix_locat): static
    {
        $this->prix_locat = $prix_locat;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;
        $this->voitureLocale = $this->getVoiture()->getLocation()->getLocale();
        $this->brandVoiture = $this->getVoiture()->getModele()->getBrand()->getName();

        return $this;
    }


    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getVoitureLocale(): ?string
    {
        return $this->voitureLocale;
    }

    public function setVoitureLocale(string $voitureLocale): static
    {

        $this->voitureLocale = $voitureLocale;

        return $this;
    }

    public function getBrandVoiture(): ?string
    {
        return $this->brandVoiture;
    }

    public function setBrandVoiture(string $brandVoiture): static
    {
        $this->brandVoiture = $brandVoiture;

        return $this;
    }

    public function getOccur(): ?int
    {
        return $this->occur;
    }
    public function __toString(): string
    {
        return sprintf(
            'Titre: %s, Prix de location: %d dh',
            $this->title ?? 'Non défini',
            $this->prix_locat/100 ?? 0
        );
    }
    public function setOccur(?int $occur): static
    {
        $this->occur = $occur;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}

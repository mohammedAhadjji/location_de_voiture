<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,nullable: true)]
    public ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptions = null;

    #[ORM\Column]
    public ?int $prix_locat = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    public ?Voiture $voiture = null;

    #[ORM\Column(length: 255)]
    private ?string $voitureLocale = null;

    #[ORM\Column(length: 255)]
    private ?string $brandVoiture = null;

    #[ORM\Column(nullable: true)]
    private ?int $occur = null;

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

    public function setOccur(?int $occur): static
    {
        $this->occur = $occur;

        return $this;
    }
}

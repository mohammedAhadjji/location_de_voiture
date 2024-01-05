<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $annee = null;

    #[ORM\Column(nullable: true)]
    private ?bool $desponibilite = null;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: ImagesVoiture::class,cascade:["persist","remove"])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Annonces::class,cascade:["remove"])]
    public Collection $annonces;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Reservation::class,cascade:["remove"])]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    private ?Modele $modele = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    private ?Type $type = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    public ?Location $location = null;

   
    public function __construct()
    {
        $this->setDesponibilite(1);
        $this->images = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->reservations = new ArrayCollection();

    }
    public function getFullName(): ?string
    {
        return $this->getType().' '.$this->getModele().' '.$this->getAnnee()->format("Y-m-d ");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function isDesponibilite(): ?bool
    {
        return $this->desponibilite;
    }

    public function setDesponibilite(?bool $desponibilite): static
    {
        $this->desponibilite = $desponibilite;

        return $this;
    }

    /**
     * @return Collection<int, ImagesVoiture>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImagesVoiture $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setVoiture($this);
        }

        return $this;
    }

    public function removeImage(ImagesVoiture $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVoiture() === $this) {
                $image->setVoiture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): static
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces->add($annonce);
            $annonce->setVoiture($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): static
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getVoiture() === $this) {
                $annonce->setVoiture(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getFullName();
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setVoiture($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVoiture() === $this) {
                $reservation->setVoiture(null);
            }
        }

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): static
    {
        $this->modele = $modele;

        return $this;
    }


  
    

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getLocation(): ?location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of location
     */ 
   
}

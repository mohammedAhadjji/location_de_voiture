<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[Vich\Uploadable]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, type:'string', nullable: true)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'name')]
    public ?File $name_file = null;

    #[ORM\OneToMany(mappedBy: 'location', targetEntity: Voiture::class)]
    public Collection $voitures;

    #[ORM\Column(length: 255)]
    private ?string $locale = null;

    #[ORM\ManyToMany(targetEntity: Brand::class, inversedBy: 'locations')]
    private Collection $Brands;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
        $this->Brands = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getLocale();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    public function getNameFile(): ?File
    {
        return $this->name_file;
    }

    public function setNameFile(?File $name_file): self
    {
        $this->name_file = $name_file;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): static
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures->add($voiture);
            $voiture->setLocation($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): static
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getLocation() === $this) {
                $voiture->setLocation(null);
            }
        }

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getBrands(): Collection
    {
        return $this->Brands;
    }

    public function addBrand(Brand $brand): static
    {
        if (!$this->Brands->contains($brand)) {
            $this->Brands->add($brand);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): static
    {
        $this->Brands->removeElement($brand);

        return $this;
    }

   
}

<?php
namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[Vich\Uploadable]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['annonces'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgBrand = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imgBrand')]
    private ?File $imgBrandFile = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Modele::class)]
    private Collection $modeles;

    #[ORM\ManyToMany(targetEntity: Location::class, mappedBy: 'brands')]
    private Collection $locations;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Voiture::class)]
    private Collection $voitures;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: ImageBrand::class,cascade:["persist","remove"])]
    private Collection $logos;

    public function __construct()
    {

        $this->modeles = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->voitures = new ArrayCollection();
        $this->logos = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImgBrand(): ?string
    {
        return $this->imgBrand;
    }

    public function setImgBrand(?string $imgBrand): static
    {
        $this->imgBrand = $imgBrand;

        return $this;
    }

    public function getImgBrandFile(): ?File
    {
        return $this->imgBrandFile;
    }

    public function setImgBrandFile(?File $imgBrandFile): static
    {
        $this->imgBrandFile = $imgBrandFile;

        return $this;
    }

    /**
     * @return Collection<int, Modele>
     */
    public function getModeles(): Collection
    {
        return $this->modeles;
    }

    public function addModele(Modele $modele): static
    {
        if (!$this->modeles->contains($modele)) {
            $this->modeles->add($modele);
            $modele->setBrand($this);
        }

        return $this;
    }

    public function removeModele(Modele $modele): static
    {
        if ($this->modeles->removeElement($modele)) {
            if ($modele->getBrand() === $this) {
                $modele->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->addBrand($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            $location->removeBrand($this);
        }

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
            $voiture->setBrand($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): static
    {
        if ($this->voitures->removeElement($voiture)) {
            if ($voiture->getBrand() === $this) {
                $voiture->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ImageBrand>
     */
    public function getLogos(): Collection
    {
        return $this->logos;
    }

    public function addLogo(ImageBrand $logo): static
    {
        if (!$this->logos->contains($logo)) {
            $this->logos->add($logo);
            $logo->setBrand($this);
        }

        return $this;
    }

    public function removeLogo(ImageBrand $logo): static
    {
        if ($this->logos->removeElement($logo)) {
            // set the owning side to null (unless already changed)
            if ($logo->getBrand() === $this) {
                $logo->setBrand(null);
            }
        }

        return $this;
    }

}

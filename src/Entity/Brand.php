<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[Vich\Uploadable]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, type: 'string', nullable: true)]
    public ?string $imgBrand = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'imgBrand')]
    private ?File $img_brand_file = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Modele::class)]
    public Collection $modeles;

    public function __construct()
    {
        $this->modeles = new ArrayCollection();
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

    public function setImgBrand(?string $imgBrand): self
    {
        $this->imgBrand = $imgBrand;

        return $this;
    }

    public function getImgBrandFile(): ?File
    {
        return $this->img_brand_file;
    }

    public function setImgBrandFile(?File $img_brand_file): self
    {
        $this->img_brand_file = $img_brand_file;

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
            // set the owning side to null (unless already changed)
            if ($modele->getBrand() === $this) {
                $modele->setBrand(null);
            }
        }

        return $this;
    }



  
}

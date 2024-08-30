<?php

namespace App\Entity;

use App\Repository\ImageBrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageBrandRepository::class)]
#[Vich\Uploadable]
class ImageBrand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'name')]
    private ?File $name_file = null;

    #[ORM\ManyToOne(inversedBy: 'logos')]
    private ?Brand $brand = null;

   
    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
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

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
    
}

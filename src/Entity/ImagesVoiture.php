<?php

namespace App\Entity;

use App\Repository\ImagesVoitureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImagesVoitureRepository::class)]
#[Vich\Uploadable]
class ImagesVoiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, type:'string', nullable: true)]
    #[Groups(['annonces'])]
    private ?string $name = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'name')]
    private ?File $name_file = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Voiture $voiture = null;

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

    public function getVoiture(): ?Voiture
    { 
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;

        return $this;
    }


}

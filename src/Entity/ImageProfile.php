<?php

namespace App\Entity;

use App\Repository\ImageProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormTypeInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageProfileRepository::class)]
#[Vich\Uploadable]
class ImageProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'name')]
    private ?File $name_file = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Users $users = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ImagesBlogsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormTypeInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImagesBlogsRepository::class)]
#[Vich\Uploadable]
class ImagesBlogs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, type:'string', nullable: true)]
    private ?string $name = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'name')]
    private ?File $name_file = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Blogs $blogs = null;

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

  
    public function getBlogs(): ?Blogs
    {
        return $this->blogs;
    }

    public function setBlogs(?Blogs $blogs): static
    {
        $this->blogs = $blogs;

        return $this;
    }
}

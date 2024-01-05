<?php

namespace App\Entity;

use App\Repository\ReductionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReductionRepository::class)]
class Reduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $reduction = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column]
    private ?int $min_day = null;

    #[ORM\Column]
    private ?int $max_day = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getMinDay(): ?int
    {
        return $this->min_day;
    }

    public function setMinDay(int $min_day): static
    {
        $this->min_day = $min_day;

        return $this;
    }

    public function getMaxDay(): ?int
    {
        return $this->max_day;
    }

    public function setMaxDay(int $max_day): static
    {
        $this->max_day = $max_day;

        return $this;
    }
}

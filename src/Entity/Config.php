<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientid = null;

    #[ORM\Column(length: 255)]
    private ?string $storekey = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientid(): ?string
    {
        return $this->clientid;
    }

    public function setClientid(?string $clientid): static
    {
        $this->clientid = $clientid;

        return $this;
    }

    public function getStorekey(): ?string
    {
        return $this->storekey;
    }

    public function setStorekey(string $storekey): static
    {
        $this->storekey = $storekey;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}

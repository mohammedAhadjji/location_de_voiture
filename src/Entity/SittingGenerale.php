<?php

namespace App\Entity;

use App\Repository\SittingGeneraleRepository as Repo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: Repo::class)]
#[Vich\Uploadable]
class SittingGenerale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, type:'string',nullable: true)]
    public ?string $logo_img = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'logo_img')]
    private ?File $logo_img_file = null;

    #[ORM\Column(length: 255, type: 'string', nullable: true)]
    private ?string $faviconImg = null;

    #[Vich\UploadableField(mapping:'products',fileNameProperty:'faviconImg')]
    private ?File $faviconImg_file = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $theme_color = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footer_adrss = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footer_mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footer_phone = null;

    #[ORM\Column(nullable: true)]
    private ?bool $custome_listing_option = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogoImg(): ?string
    {
        return $this->logo_img;
    }

    public function setLogoImg(?string $logo_img): static
    {
        $this->logo_img = $logo_img;

        return $this;
    }
    public function getLogoImgfile(): ?File
    {
        return $this->logo_img_file;
    }


    public function setLogoImgfile(?File $logo_img_file): static
    {
        $this->logo_img = $logo_img_file;

        return $this;
    }

    public function getFaviconImg(): ?string
    {
        return $this->faviconImg;
    }

    public function setFaviconImg(?string $faviconImg): ?self
    {
        $this->faviconImg = $faviconImg;

        return $this;
    }

    public function getfaviconImgFile(): ?File
    {
        return $this->faviconImg_file;
    }


    public function setfaviconImgFile(?File $faviconImg_file): ?self
    {
        $this->faviconImg_file = $faviconImg_file;

        return $this;
    }

    public function getThemeColor(): ?string
    {
        return $this->theme_color;
    }

    public function setThemeColor(?string $theme_color): static
    {
        $this->theme_color = $theme_color;

        return $this;
    }

    public function getFooterAdrss(): ?string
    {
        return $this->footer_adrss;
    }

    public function setFooterAdrss(?string $footer_adrss): static
    {
        $this->footer_adrss = $footer_adrss;

        return $this;
    }

    public function getFooterMail(): ?string
    {
        return $this->footer_mail;
    }

    public function setFooterMail(?string $footer_mail): static
    {
        $this->footer_mail = $footer_mail;

        return $this;
    }

    public function getFooterPhone(): ?string
    {
        return $this->footer_phone;
    }

    public function setFooterPhone(?string $footer_phone): static
    {
        $this->footer_phone = $footer_phone;

        return $this;
    }

    public function isCustomeListingOption(): ?bool
    {
        return $this->custome_listing_option;
    }

    public function setCustomeListingOption(?bool $custome_listing_option): static
    {
        $this->custome_listing_option = $custome_listing_option;

        return $this;
    }
    
}

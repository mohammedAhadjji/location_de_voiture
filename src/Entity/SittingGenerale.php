<?php

namespace App\Entity;

use App\Repository\SittingGeneraleRepository as Repo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\OneToMany(mappedBy: 'sittingGenerale', targetEntity: Categories::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'sittingGenerale', targetEntity: Brand::class)]
    private Collection $brands;

    #[ORM\OneToMany(mappedBy: 'sittingGenerale', targetEntity: Location::class)]
    private Collection $location;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $iframeVideo = null;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video = null;

    #[Vich\UploadableField(mapping:'video',fileNameProperty:'video')]
    private ?File $name_file = null;
    
    public function getNameFile(): ?File
    {
        return $this->name_file;
    }

    public function setNameFile(?File $name_file): self
    {
        $this->name_file = $name_file;

        return $this;
    }
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->brands = new ArrayCollection();
        $this->location = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setSittingGenerale($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getSittingGenerale() === $this) {
                $category->setSittingGenerale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): static
    {
        if (!$this->brands->contains($brand)) {
            $this->brands->add($brand);
            $brand->setSittingGenerale($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): static
    {
        if ($this->brands->removeElement($brand)) {
            // set the owning side to null (unless already changed)
            if ($brand->getSittingGenerale() === $this) {
                $brand->setSittingGenerale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocation(): Collection
    {
        return $this->location;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->location->contains($location)) {
            $this->location->add($location);
            $location->setSittingGenerale($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->location->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getSittingGenerale() === $this) {
                $location->setSittingGenerale(null);
            }
        }

        return $this;
    }

    public function getIframeVideo(): ?string
    {
        return $this->iframeVideo;
    }

    public function setIframeVideo(?string $iframeVideo): static
    {
        $this->iframeVideo = $iframeVideo;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;

        return $this;
    }
    

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

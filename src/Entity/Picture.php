<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $urlName = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlName(): ?string
    {
        return $this->urlName;
    }

    public function setUrlName(string $urlName): static
    {
        $this->urlName = $urlName;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
    

}

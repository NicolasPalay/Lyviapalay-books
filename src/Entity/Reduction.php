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

    #[ORM\Column(length: 10)]
    private ?string $reductCode = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column]
    private ?bool $actived = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $couponId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReductCode(): ?string
    {
        return $this->reductCode;
    }

    public function setReductCode(string $reductCode): static
    {
        $this->reductCode = $reductCode;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function isActived(): ?bool
    {
        return $this->actived;
    }

    public function setActived(bool $actived): static
    {
        $this->actived = $actived;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getCouponId(): ?string
    {
        return $this->couponId;
    }

    public function setCouponId(?string $couponId): static
    {
        $this->couponId = $couponId;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CitationRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: CitationRepository::class)]
class Citation
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    private ?string $Author = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $udpateAt = null;
public function __construct(){
    $this->createAt = new \DateTimeImmutable();
    $this->udpateAt = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUdpateAt(): ?\DateTimeImmutable
    {
        return $this->udpateAt;
    }

    public function setUdpateAt(\DateTimeImmutable $udpateAt): static
    {
        $this->udpateAt = $udpateAt;

        return $this;
    }
}

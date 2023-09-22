<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    //#[Assert\Callback("App\Validator\ForbiddenWordsValidator")]
    #[Assert\NotBlank(message: 'Veuillez le nom du produit')]
    #[Assert\Length(min: 10, max: 500)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Blog $blog = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: false)]
    private ?bool $rgpt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): static
    {
        $this->blog = $blog;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function isRgpt(): ?bool
    {
        return $this->rgpt;
    }

    public function setRgpt(?bool $rgpt): static
    {
        $this->rgpt = $rgpt;

        return $this;
    }
    public function validateComment(ExecutionContextInterface $context, $payload)
    {
        // Utilisez "content" comme chemin
        $context->addViolation('Le commentaire contient des mots indésirables.', ['%string%' => $this->getContent()]);
    }
}

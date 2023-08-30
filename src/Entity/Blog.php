<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Picture::class, cascade: ["persist"])]
    private Collection $picture;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $publish = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $excerpt = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'Blog', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->picture = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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
    public function __toString()
    {
        return $this->getTitle(); // Par exemple, renvoyer le titre du blog
    }
    /**
     * @return Collection<int, Picture>
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->picture->contains($picture)) {
            $this->picture->add($picture);
            $picture->setBlog($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->picture->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getBlog() === $this) {
                $picture->setBlog(null);
            }
        }

        return $this;
    }



    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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

    public function isPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): static
    {
        $this->publish = $publish;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setBlog($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBlog() === $this) {
                $comment->setBlog(null);
            }
        }

        return $this;
    }
}

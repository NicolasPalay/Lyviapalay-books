<?php

namespace App\Entity;

use App\Repository\DecicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecicationRepository::class)]
class Decication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?\DateTime $dateDedication = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateDedicationEnd = null;

    #[ORM\OneToMany(mappedBy: 'dedication', targetEntity: Blog::class)]
    private Collection $blogs;

    public function __construct()
    {
        $this->blogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDateDedication(): ?\DateTime
    {
        return $this->dateDedication;
    }

    public function setDateDedication(\DateTime $dateDedication): static
    {
        $this->dateDedication = $dateDedication;

        return $this;
    }

    public function getDateDedicationEnd(): ?\DateTime
    {
        return $this->dateDedicationEnd;
    }

    public function setDateDedicationEnd(?\DateTime $dateDedicationEnd): static
    {
        $this->dateDedicationEnd = $dateDedicationEnd;

        return $this;
    }

    /**
     * @return Collection<int, Blog>
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): static
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs->add($blog);
            $blog->setDedication($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): static
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getDedication() === $this) {
                $blog->setDedication(null);
            }
        }

        return $this;
    }
}

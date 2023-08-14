<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[UniqueEntity(fields: ['name'], message: "Le nom de l'adresse doit être unique")]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez le nom de l\'adresse')]
    #[Assert\Length(min: 10,
        max: 255,
        maxMessage: 'Le nom de l\'adresse doit faire moins de 255 caractères',
        minMessage: 'Le nom de l\'adresse doit faire plus de 10 caractères')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez l\'adresse')]
    #[Assert\Length(min: 10,
        max: 255,
        maxMessage: 'L\'adresse doit faire moins de 255 caractères',
        minMessage: 'L\'adresse doit faire plus de 10 caractères')]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez le code postal')]
    #[Assert\Length(min: 5,
        max: 5,
        maxMessage: 'Le code postal doit faire 5 caractères',
        minMessage: 'Le code postal doit faire 5 caractères')]
    private ?string $postal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez la ville')]
    #[Assert\Length(min: 2,
        max: 255,
        maxMessage: 'La ville doit faire moins de 255 caractères',
        minMessage: 'La ville doit faire plus de 2 caractères')]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez le téléphone')]
    #[Assert\Length(min: 10,
        max: 10,
        maxMessage: 'Le téléphone doit faire 10 caractères',
        minMessage: 'Le téléphone doit faire 10 caractères')]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]

    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Lastname = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): static
    {
        $this->postal = $postal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(?string $Lastname): static
    {
        $this->Lastname = $Lastname;

        return $this;
    }
}

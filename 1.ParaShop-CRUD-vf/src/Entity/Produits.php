<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'le nom du produit ne contient pas des nombre',
        match: true
    )]
    private ?string $NomProduit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $EtatProduit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Positive]
    private ?string $PrixProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Length(
        min: 50,
        max: 255,
        minMessage: 'insuffisant {{ limit }}',
        maxMessage: 'trop long {{ limit }} ',
    )]
    private ?string $DescriptionProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StatusProduit = null;

    #[ORM\Column(length: 255, nullable: true)]

    private ?string $localisationProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $Brand = null;

//    #[ORM\ManyToOne]
//    #[Assert\NotBlank (message:'champ obligatoire')]
//    private ?CategorieProduit $CategorieProduit = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $CreatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $UpdatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Details = null;

    #[ORM\Column]
    private ?bool $Authorisation = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?CategorieProduit $CategorieProduit = null;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->NomProduit;
    }

    public function setNomProduit(string $NomProduit): self
    {
        $this->NomProduit = $NomProduit;

        return $this;
    }

    public function getEtatProduit(): ?string
    {
        return $this->EtatProduit;
    }

    public function setEtatProduit(string $EtatProduit): self
    {
        $this->EtatProduit = $EtatProduit;

        return $this;
    }

    public function getPrixProduit(): ?string
    {
        return $this->PrixProduit;
    }

    public function setPrixProduit(string $PrixProduit): self
    {
        $this->PrixProduit = $PrixProduit;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->DescriptionProduit;
    }

    public function setDescriptionProduit(?string $DescriptionProduit): self
    {
        $this->DescriptionProduit = $DescriptionProduit;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->ImagePath;
    }

    public function setImagePath(?string $ImagePath): self
    {
        $this->ImagePath = $ImagePath;

        return $this;
    }

    public function getStatusProduit(): ?string
    {
        return $this->StatusProduit;
    }

    public function setStatusProduit(?string $StatusProduit): self
    {
        $this->StatusProduit = $StatusProduit;

        return $this;
    }

    public function getLocalisationProduit(): ?string
    {
        return $this->localisationProduit;
    }

    public function setLocalisationProduit(?string $localisationProduit): self
    {
        $this->localisationProduit = $localisationProduit;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->Brand;
    }

    public function setBrand(?string $Brand): self
    {
        $this->Brand = $Brand;

        return $this;
    }





    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->Details;
    }

    public function setDetails(?string $Details): self
    {
        $this->Details = $Details;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->CreatedAt = new \DateTime();
        $this->UpdatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->UpdatedAt = new \DateTime();
    }

    public function isAuthorisation(): ?bool
    {
        return $this->Authorisation;
    }

    public function setAuthorisation(bool $Authorisation): self
    {
        $this->Authorisation = $Authorisation;

        return $this;
    }

    public function getCategorieProduit(): ?CategorieProduit
    {
        return $this->CategorieProduit;
    }

    public function setCategorieProduit(?CategorieProduit $CategorieProduit): self
    {
        $this->CategorieProduit = $CategorieProduit;

        return $this;
    }

}

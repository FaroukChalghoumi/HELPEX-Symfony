<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $EtatProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $PrixProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DescriptionProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StatusProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisationProduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Brand = null;

    #[ORM\ManyToOne]
    private ?CategorieProduit $CategorieProduit = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



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

    public function getCategorieProduit(): ?CategorieProduit
    {
        return $this->CategorieProduit;
    }

    public function setCategorieProduit(?CategorieProduit $CategorieProduit): self
    {
        $this->CategorieProduit = $CategorieProduit;

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




}

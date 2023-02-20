<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieProduitRepository::class)]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'name must be only characters',
        match: true
    )]
    private ?string $NomCatProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCatProduit(): ?string
    {
        return $this->NomCatProduit;
    }

    public function setNomCatProduit(string $NomCatProduit): self
    {
        $this->NomCatProduit = $NomCatProduit;

        return $this;
    }
    public function __toString(): string
    {
        return  $this->getNomCatProduit();
    }
}

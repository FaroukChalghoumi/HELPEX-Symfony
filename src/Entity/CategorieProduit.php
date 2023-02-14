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
}

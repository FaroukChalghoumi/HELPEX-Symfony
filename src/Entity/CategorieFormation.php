<?php

namespace App\Entity;

use App\Repository\CategorieFormationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieFormationRepository::class)]
class CategorieFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCategorieFormation = null;

    //#[ORM\Column(length: 255, nullable: true)]
    #[ORM\Column(length: 255)]
    private ?string $descriptionCategorieFormation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorieFormation(): ?string
    {
        return $this->nomCategorieFormation;
    }

    public function setNomCategorieFormation(string $nomCategorieFormation): self
    {
        $this->nomCategorieFormation = $nomCategorieFormation;

        return $this;
    }

    public function getDescriptionCategorieFormation(): ?string
    {
        return $this->descriptionCategorieFormation;
    }

    public function setDescriptionCategorieFormation(string $descriptionCategorieFormation): self
    {
        $this->descriptionCategorieFormation = $descriptionCategorieFormation;

        return $this;
    }
}

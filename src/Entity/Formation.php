<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomFormation = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionFormation = null;

    #[ORM\Column]
    private ?float $coutFormation = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombreDePlace = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?CategorieFormation $categorieFormation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    public function setNomFormation(string $nomFormation): self
    {
        $this->nomFormation = $nomFormation;

        return $this;
    }

    public function getDescriptionFormation(): ?string
    {
        return $this->descriptionFormation;
    }

    public function setDescriptionFormation(string $descriptionFormation): self
    {
        $this->descriptionFormation = $descriptionFormation;

        return $this;
    }

    public function getCoutFormation(): ?float
    {
        return $this->coutFormation;
    }

    public function setCoutFormation(float $coutFormation): self
    {
        $this->coutFormation = $coutFormation;

        return $this;
    }

    public function getNombreDePlace(): ?int
    {
        return $this->nombreDePlace;
    }

    public function setNombreDePlace(?int $nombreDePlace): self
    {
        $this->nombreDePlace = $nombreDePlace;

        return $this;
    }

    public function getCategorieFormation(): ?CategorieFormation
    {
        return $this->categorieFormation;
    }

    public function setCategorieFormation(?CategorieFormation $categorieFormation): self
    {
        $this->categorieFormation = $categorieFormation;

        return $this;
    }
}

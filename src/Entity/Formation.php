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

    #[ORM\Column(length: 500)]
    private ?string $descriptionFormation = null;

    #[ORM\Column]
    private ?float $coutFormation = null;

    #[ORM\Column(nullable: true)]
    private ?int $NombreDePlace = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duree = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?CategorieFormation $idCategorieFormation = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    private ?Centre $idCentre = null;

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
        return $this->NombreDePlace;
    }

    public function setNombreDePlace(?int $NombreDePlace): self
    {
        $this->NombreDePlace = $NombreDePlace;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getIdCategorieFormation(): ?CategorieFormation
    {
        return $this->idCategorieFormation;
    }

    public function setIdCategorieFormation(?CategorieFormation $idCategorieFormation): self
    {
        $this->idCategorieFormation = $idCategorieFormation;

        return $this;
    }

    public function getIdCentre(): ?Centre
    {
        return $this->idCentre;
    }

    public function setIdCentre(?Centre $idCentre): self
    {
        $this->idCentre = $idCentre;

        return $this;
    }
}

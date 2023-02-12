<?php

namespace App\Entity;

use App\Repository\InscriptionFormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionFormationRepository::class)]
class InscriptionFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateInscriptionFormation = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscriptionFormation(): ?\DateTimeInterface
    {
        return $this->dateInscriptionFormation;
    }

    public function setDateInscriptionFormation(?\DateTimeInterface $dateInscriptionFormation): self
    {
        $this->dateInscriptionFormation = $dateInscriptionFormation;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }
}

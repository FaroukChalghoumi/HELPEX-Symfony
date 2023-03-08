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

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateInscriptionFormation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statusFormation = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptionFormations')]
    private ?Formation $formations = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptionFormations')]
    private ?User $user = null;



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

    public function getStatusFormation(): ?string
    {
        return $this->statusFormation;
    }

    public function setStatusFormation(?string $statusFormation): self
    {
        $this->statusFormation = $statusFormation;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function getFormations(): ?Formation
    {
        return $this->formations;
    }

    public function setFormations(?Formation $formations): self
    {
        $this->formations = $formations;

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

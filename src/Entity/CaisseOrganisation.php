<?php

namespace App\Entity;

use App\Repository\CaisseOrganisationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CaisseOrganisationRepository::class)]
class CaisseOrganisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantCaisseOrg = 0;

    #[ORM\Column]
    #[Assert\Regex(
        pattern: '/^[0-9]+$/i',
        match: true
    )]
    #[Assert\NotBlank]
    private ?float $goal = null;

    #[ORM\ManyToOne(inversedBy: 'Caisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $organisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantCaisseOrg(): ?float
    {
        return $this->montantCaisseOrg;
    }

    public function setMontantCaisseOrg(?float $montantCaisseOrg): self
    {
        $this->montantCaisseOrg = $montantCaisseOrg;

        return $this;
    }

    public function getGoal(): ?float
    {
        return $this->goal;
    }

    public function setGoal(float $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}

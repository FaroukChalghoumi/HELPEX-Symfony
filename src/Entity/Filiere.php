<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: FiliereRepository::class)]
class Filiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]

    private ?string $NomFiliere = null;

    #[ORM\OneToMany(mappedBy: 'filiere', targetEntity: User::class , orphanRemoval: true)]
    private Collection $users;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $DescriptionFiliere = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFiliere(): ?string
    {
        return $this->NomFiliere;
    }

    public function setNomFiliere(string $NomFiliere): self
    {
        $this->NomFiliere = $NomFiliere;

        return $this;
    }

    public function __toString()
    {
        return $this->NomFiliere; 
    }


    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setFiliere($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getFiliere() === $this) {
                $user->setFiliere(null);
            }
        }

        return $this;
    }

    public function getDescriptionFiliere(): ?string
    {
        return $this->DescriptionFiliere;
    }

    public function setDescriptionFiliere(string $DescriptionFiliere): self
    {
        $this->DescriptionFiliere = $DescriptionFiliere;

        return $this;
    }
}

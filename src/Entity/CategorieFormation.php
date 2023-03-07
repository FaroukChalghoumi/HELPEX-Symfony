<?php

namespace App\Entity;

use App\Repository\CategorieFormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategorieFormationRepository::class)]
class CategorieFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $nomCategorieFormation = null;



    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Length(
        min: 10,
        minMessage: 'description insuffisante minimum {{ limit }} caractères',
    )]
    #[Assert\Regex('/^\w+/')]
    private ?string $descriptionCategorieFormation = null;

    #[ORM\OneToMany(mappedBy: 'idCategorieFormation', targetEntity: Formation::class, orphanRemoval: true)]
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

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

    public function setDescriptionCategorieFormation(?string $descriptionCategorieFormation): self
    {
        $this->descriptionCategorieFormation = $descriptionCategorieFormation;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setIdCategorieFormation($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getIdCategorieFormation() === $this) {
                $formation->setIdCategorieFormation(null);
            }
        }

        return $this;
    }

    public function  __toString(): string
    {
        return $this->getNomCategorieFormation();
    }
}

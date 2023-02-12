<?php

namespace App\Entity;

use App\Repository\CategorieFormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'categorieFormation', targetEntity: Formation::class)]
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

    public function setDescriptionCategorieFormation(string $descriptionCategorieFormation): self
    {
        $this->descriptionCategorieFormation = $descriptionCategorieFormation;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getformations(): Collection
    {
        return $this->formations;
    }

    public function addformations(Formation $formations): self
    {
        if (!$this->formations->contains($formations)) {
            $this->formations->add($formations);
            $formations->setCategorieFormation($this);
        }

        return $this;
    }

    public function removeformations(Formation $formations): self
    {
        if ($this->formations->removeElement($formations)) {
            // set the owning side to null (unless already changed)
            if ($formations->getCategorieFormation() === $this) {
                $formations->setCategorieFormation(null);
            }
        }

        return $this;
    }
}

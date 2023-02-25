<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
//use Doctrine\Common\Collections\Collection;
//use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieProduitRepository::class)]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'name must be only characters',
        match: true
    )]
    private ?string $NomCatProduit = null;

    #[ORM\OneToMany(mappedBy: 'CategorieProduit', targetEntity: Produits::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

//    #[ORM\OneToMany(mappedBy: 'Produits', targetEntity: Produits::class)]
//    private Collection $produits;

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
    public function __toString(): string
    {
        return  $this->getNomCatProduit();
    }
//    /**
//     * @return Collection<int, Poste>
//     */
//    public function getPostes(): Collection
//    {
//        return $this->produits;
//    }
//
//    public function addProduit(Poste $produit): self
//    {
//        if (!$this->produits->contains($produit) {
//            $this->produits->add($produit);
//            $produit->setCategorie($this);
//        }
//
//        return $this;
//    }
//
//    public function removePoste(Poste $poste): self
//    {
//        if ($this->postes->removeElement($poste)) {
//            // set the owning side to null (unless already changed)
//            if ($poste->getCategorie() === $this) {
//                $poste->setCategorie(null);
//            }
//        }

//        return $this;
//    }

/**
 * @return Collection<int, Produits>
 */
public function getProduits(): Collection
{
    return $this->produits;
}

public function addProduit(Produits $produit): self
{
    if (!$this->produits->contains($produit)) {
        $this->produits->add($produit);
        $produit->setCategorieProduit($this);
    }

    return $this;
}

public function removeProduit(Produits $produit): self
{
    if ($this->produits->removeElement($produit)) {
        // set the owning side to null (unless already changed)
        if ($produit->getCategorieProduit() === $this) {
            $produit->setCategorieProduit(null);
        }
    }

    return $this;
}
}

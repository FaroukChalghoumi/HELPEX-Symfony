<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\EntityListeners({"App\EventListener\YourEntityListener"})
 */
#[ORM\Entity(repositoryClass: PosteRepository::class)]
class Poste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups ("post:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Length(
        min: 4,
        max: 20,
        minMessage: 'insuffisant {{ limit }}',
        maxMessage: 'trop long {{ limit }} ',
    )]
    #[Assert\Regex(pattern:'/^[0-9]+$/i', match:false,message:'c"ant only be numbers')]
    #[groups ("post:read")]
    private ?string $titre = null;
  
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[groups ("post:read")]

    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $multimedia = null;

    #[ORM\Column]
    private ?int $compteurvote = null;

    #[ORM\OneToMany(mappedBy: 'poste', targetEntity: Commentaire::class)]
    private ?Collection $commentaire;

    #[ORM\ManyToOne(inversedBy: 'postes')]
    private ?Categorieposte $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'postes')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'poste', targetEntity: Postelikes::class)]
    private Collection $likes;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->compteurvote = 0;
        $this->commentaire = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
   
    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMultimedia(): ?string
    {
        return $this->multimedia;
    }

    public function setMultimedia(?string $multimedia): self
    {
        $this->multimedia = $multimedia;

        return $this;
    }

    public function getCompteurvote(): ?int
    {
        return $this->compteurvote;
    }

    public function setCompteurvote(int $compteurvote): self
    {
        $this->compteurvote = $compteurvote;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setPoste($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPoste() === $this) {
                $commentaire->setPoste(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorieposte
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorieposte $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
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

    /**
     * @return Collection<int, Postelikes>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Postelikes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPoste($this);
        }

        return $this;
    }

    public function removeLike(Postelikes $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPoste() === $this) {
                $like->setPoste(null);
            }
        }

        return $this;
    }
    public function isliked(User $user)
    {
        foreach($this->likes as $like)
        {
            if($like->getUser() === $user)return true;
        }
        return false;
    }
}

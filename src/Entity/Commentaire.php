<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $compteurvote = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?Poste $poste = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user = null;
    public function __construct()
    {
       
        $this->date = new \DateTime();
        $this->compteurvote = 0;
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }
    public function __toString()
    {
        return $this->getDescription();
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }
}

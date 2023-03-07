<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'minumum 5 caratéres',
        maxMessage: 'ùaximum 50 caracteres',
    )]
    #[Groups(['item'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups(['item'])]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column]
    #[Groups(['item'])]
    private ?bool $is_complete = null;

    #[ORM\ManyToOne(inversedBy: 'list_items')]
    private Tasks $tasks ;



    #[Groups(['item'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function isIsComplete(): ?bool
    {
        return $this->is_complete;
    }

    public function setIsComplete(bool $is_complete): self
    {
        $this->is_complete = $is_complete;

        return $this;
    }

    public function getTasks(): ?Tasks
    {
        return $this->tasks;
    }

    public function setTasks(?Tasks $tasks): self
    {
        $this->tasks = $tasks;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}

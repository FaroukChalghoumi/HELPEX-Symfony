<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime ;
use http\Message;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'minumum 5 caratéres',
        maxMessage: 'ùaximum 50 caracteres',
    )]
    private ?string $titre = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(value: 'today',message: "la date soit étre supérieur à la date d'aujourd'hui")]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(propertyPath :"start_date"
    ,message:"error date doit etre superieur de date debut")]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?bool $is_valid = null;

    #[ORM\OneToMany(mappedBy: 'tasks', targetEntity: Item::class)]
    #[Assert\NotBlank]
    private Collection $list_items;

    public function __construct()
    {
        $this->list_items = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getListItems(): Collection
    {
        return $this->list_items;
    }

    public function addListItem(Item $listItem): self
    {
        if (!$this->list_items->contains($listItem)) {
            $this->list_items->add($listItem);
            $listItem->setTasks($this);
        }

        return $this;
    }

    public function removeListItem(Item $listItem): self
    {
        if ($this->list_items->removeElement($listItem)) {
            // set the owning side to null (unless already changed)
            if ($listItem->getTasks() === $this) {
                $listItem->setTasks(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitre();
    }
    private ?String $color ;
    public function rand_color() {
        $this->color= sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
    public static function randm_image(){
        $imagesDir = 'images/tasks/';

        $images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $randomImage = $images[array_rand($images)];
    }

}

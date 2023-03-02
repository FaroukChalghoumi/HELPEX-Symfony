<?php

namespace App\Entity;

use App\Repository\AccompagnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccompagnementRepository::class)]
class Accompagnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Column( name:"task_id")]
    private ?Tasks $task = null;

    #[ORM\Column]
    private ?bool $is_accepted = null;

    #[ORM\ManyToOne(inversedBy: 'accompagnements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\ManyToOne(inversedBy: 'accompagnements_pro')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user_pro = null;




    public function __construct()
    {
    }

    public function getId(): ?int
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







    public function getTask(): ?tasks
    {
        return $this->task;
    }

    public function setTask(tasks $task_id): self
    {
        $this->task= $task_id;

        return $this;
    }

    public function isIsAccepted(): ?bool
    {
        return $this->is_accepted;
    }

    public function setIsAccepted(bool $is_accepted): self
    {
        $this->is_accepted = $is_accepted;

        return $this;
    }

    public function getUserPro(): ?User
    {
        return $this->user_pro;
    }

    public function setUserPro(?User $user_pro): self
    {
        $this->user_pro = $user_pro;

        return $this;
    }
}

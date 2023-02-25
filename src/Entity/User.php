<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    /**dropdownmenu homme femme */
    private ?string $sexe = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    /**dropmenu regions tn */
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'numero telephone non valide',
        maxMessage: 'numero telephone non valide',
    )]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdp = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $bio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank (message:'champ obligatoire')]   
   // #[Assert\LessThanOrEqual("-18 years", message:"You should be at least 18 years old.")] 
    private ?\DateTimeInterface $date_naissance = null;
    

    #[ORM\Column(type: Types::TEXT, nullable: true)] /***selon role pour pro user */
   /**  #[Assert\NotBlank (message:'champ obligatoire')] */  
   private ?string $diplome = null;

    #[ORM\Column(nullable: true)]
   /**  #[Assert\NotBlank (message:'champ obligatoire')] */  
    private ?float $tarif = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Filiere $filiere = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isEnabled = null;

    public function __construct()
{
    $this->isEnabled = true;
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getPdp(): ?string
    {
        return $this->pdp;
    }

    public function setPdp(?string $pdp): self
    {
        $this->pdp = $pdp;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(?float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function isIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    
}

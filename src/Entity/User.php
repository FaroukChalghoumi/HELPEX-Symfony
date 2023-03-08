<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[groups ("post:read")]
        private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[groups ("post:read")]
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
    #[groups ("post:read")]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[groups ("post:read")]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    #[groups ("post:read")]
    #[Assert\NotBlank (message:'champ obligatoire')]
    /**dropdownmenu homme femme */
    private ?string $sexe = null;

    #[ORM\Column(type: Types::TEXT)]
    #[groups ("post:read")]
    #[Assert\NotBlank (message:'champ obligatoire')]
    /**dropmenu regions tn */
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[groups ("post:read")]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'numero telephone non valide',
        maxMessage: 'numero telephone non valide',
    )]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[groups ("post:read")]
    private ?string $pdp = null;

    #[ORM\Column(type: Types::TEXT)]
    #[groups ("post:read")]
    #[Assert\NotBlank (message:'champ obligatoire')]
    private ?string $bio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]   
   // #[Assert\LessThanOrEqual("-18 years", message:"You should be at least 18 years old.")] 
    private ?\DateTimeInterface $date_naissance = null;
    

    #[ORM\Column(type: Types::TEXT, nullable: true)] /***selon role pour pro user */
   /**  #[Assert\NotBlank (message:'champ obligatoire')] */  
   #[groups ("post:read")]
   private ?string $diplome = null;

    #[ORM\Column(nullable: true)]
   /**  #[Assert\NotBlank (message:'champ obligatoire')] */  
   #[groups ("post:read")]
    private ?float $tarif = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Filiere $filiere = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isEnabled = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: InscriptionFormation::class)]
    private Collection $inscriptionFormations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Poste::class)]
    private Collection $postes;

//
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Accompagnement::class, orphanRemoval: true)]
    private Collection $accompagnements;

    #[ORM\OneToMany(mappedBy: 'user_pro', targetEntity: Accompagnement::class)]
    private ?Collection $accompagnements_pro=null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Accompagnement $accompagnement = null;



    public function __construct()
{
    $this->isEnabled = true;
    $this->inscriptionFormations = new ArrayCollection();
    $this->postes = new ArrayCollection();

    $this->accompagnements = new ArrayCollection();
    $this->accompagnements_pro = new ArrayCollection();
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

    /**
     * @return Collection<int, InscriptionFormation>
     */
    public function getInscriptionFormations(): Collection
    {
        return $this->inscriptionFormations;
    }

    public function addInscriptionFormation(InscriptionFormation $inscriptionFormation): self
    {
        if (!$this->inscriptionFormations->contains($inscriptionFormation)) {
            $this->inscriptionFormations->add($inscriptionFormation);
            $inscriptionFormation->setUser($this);
        }

        return $this;
    }

    public function removeInscriptionFormation(InscriptionFormation $inscriptionFormation): self
    {
        if ($this->inscriptionFormations->removeElement($inscriptionFormation)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionFormation->getUser() === $this) {
                $inscriptionFormation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Poste>
     */
    public function getPostes(): Collection
    {
        return $this->postes;
    }

    public function addPoste(Poste $poste): self
    {
        if (!$this->postes->contains($poste)) {
            $this->postes->add($poste);
            $poste->setUser($this);
        }

        return $this;
    }

    public function removePoste(Poste $poste): self
    {
        if ($this->postes->removeElement($poste)) {
            // set the owning side to null (unless already changed)
            if ($poste->getUser() === $this) {
                $poste->setUser(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, Accompagnement>
     */
    public function getAccompagnements(): Collection
    {
        return $this->accompagnements;
    }

    public function addAccompagnement(Accompagnement $accompagnement): self
    {
        if (!$this->accompagnements->contains($accompagnement)) {
            $this->accompagnements->add($accompagnement);
            $accompagnement->setUser($this);
        }

        return $this;
    }

    public function removeAccompagnement(Accompagnement $accompagnement): self
    {
        if ($this->accompagnements->removeElement($accompagnement)) {
            // set the owning side to null (unless already changed)
            if ($accompagnement->getUser() === $this) {
                $accompagnement->setUser(null);
            }
        }

        return $this;
    }

    public function getAccompagnement(): ?Accompagnement
    {
        return $this->accompagnement;
    }

    public function setAccompagnement(?Accompagnement $accompagnement): self
    {
        $this->accompagnement = $accompagnement;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom() ;}



    /**
     * @return Collection<int, Accompagnement>
     */
    public function getAccompagnementsPro(): Collection
    {
        return $this->accompagnements_pro;
    }

    public function addAccompagnementsPro(Accompagnement $accompagnementsPro): self
    {
        if (!$this->accompagnements_pro->contains($accompagnementsPro)) {
            $this->accompagnements_pro->add($accompagnementsPro);
            $accompagnementsPro->setUserPro($this);
        }

        return $this;
    }

    public function removeAccompagnementsPro(Accompagnement $accompagnementsPro): self
    {
        if ($this->accompagnements_pro->removeElement($accompagnementsPro)) {
            // set the owning side to null (unless already changed)
            if ($accompagnementsPro->getUserPro() === $this) {
                $accompagnementsPro->setUserPro(null);
            }
        }

        return $this;
    }


}

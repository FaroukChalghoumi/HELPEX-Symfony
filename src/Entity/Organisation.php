<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
class Organisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $descriptionOrganisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[a-z]+@gmail.com+$/i',
        message: 'mail must be @gmail.com',
        match: true
    )]
    #[Assert\NotBlank]
    private ?string $emailOrganisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]+[0-9]$/i',
        message: 'Number must be only numbers (phone number has exactly 8 characters)',
        match: true
    )]
    #[Assert\NotBlank]
    private ?string $numTelOrganisation = null;

    #[ORM\Column(length: 255)]

    private ?string $documentOrganisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $paymentInfo = null;

    #[ORM\OneToMany(mappedBy: 'organisation', targetEntity: CaisseOrganisation::class, orphanRemoval: true)]
    private Collection $Caisses;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'name must be only characters',
        match: true
    )]
    #[Assert\NotBlank(
        message: 'champ obligatoire'
    )]
    private ?string $NomOrg = null;

    #[ORM\Column(length: 255)]

    private ?string $logoOrg = null;

    public function __construct()
    {
        $this->Caisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionOrganisation(): ?string
    {
        return $this->descriptionOrganisation;
    }

    public function setDescriptionOrganisation(string $descriptionOrganisation): self
    {
        $this->descriptionOrganisation = $descriptionOrganisation;

        return $this;
    }

    public function getEmailOrganisation(): ?string
    {
        return $this->emailOrganisation;
    }

    public function setEmailOrganisation(string $emailOrganisation): self
    {
        $this->emailOrganisation = $emailOrganisation;

        return $this;
    }

    public function getNumTelOrganisation(): ?string
    {
        return $this->numTelOrganisation;
    }

    public function setNumTelOrganisation(string $numTelOrganisation): self
    {
        $this->numTelOrganisation = $numTelOrganisation;

        return $this;
    }

    public function getDocumentOrganisation(): ?string
    {
        return $this->documentOrganisation;
    }

    public function setDocumentOrganisation(string $documentOrganisation): self
    {
        $this->documentOrganisation = $documentOrganisation;

        return $this;
    }

    public function getPaymentInfo(): ?string
    {
        return $this->paymentInfo;
    }

    public function setPaymentInfo(string $paymentInfo): self
    {
        $this->paymentInfo = $paymentInfo;

        return $this;
    }

    /**
     * @return Collection<int, CaisseOrganisation>
     */
    public function getCaisses(): Collection
    {
        return $this->Caisses;
    }

    public function addCaiss(CaisseOrganisation $caiss): self
    {
        if (!$this->Caisses->contains($caiss)) {
            $this->Caisses->add($caiss);
            $caiss->setOrganisation($this);
        }

        return $this;
    }

    public function removeCaiss(CaisseOrganisation $caiss): self
    {
        if ($this->Caisses->removeElement($caiss)) {
            // set the owning side to null (unless already changed)
            if ($caiss->getOrganisation() === $this) {
                $caiss->setOrganisation(null);
            }
        }

        return $this;
    }

    public function getNomOrg(): ?string
    {
        return $this->NomOrg;
    }

    public function setNomOrg(string $NomOrg): self
    {
        $this->NomOrg = $NomOrg;

        return $this;
    }
    public function __toString(): string{
        return $this->NomOrg;
    }

    public function getLogoOrg(): ?string
    {
        return $this->logoOrg;
    }

    public function setLogoOrg(string $logoOrg): self
    {
        $this->logoOrg = $logoOrg;

        return $this;
    }
}

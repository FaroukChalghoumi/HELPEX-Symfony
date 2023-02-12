<?php

namespace App\Entity;

use App\Repository\CentreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentreRepository::class)]
class Centre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCentre = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseCentre = null;

    #[ORM\Column(length: 255)]
    private ?string $emailCentre = null;

    #[ORM\Column]
    private ?int $telephoneCentre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteWebCentre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCentre(): ?string
    {
        return $this->nomCentre;
    }

    public function setNomCentre(string $nomCentre): self
    {
        $this->nomCentre = $nomCentre;

        return $this;
    }

    public function getAdresseCentre(): ?string
    {
        return $this->adresseCentre;
    }

    public function setAdresseCentre(string $adresseCentre): self
    {
        $this->adresseCentre = $adresseCentre;

        return $this;
    }

    public function getEmailCentre(): ?string
    {
        return $this->emailCentre;
    }

    public function setEmailCentre(string $emailCentre): self
    {
        $this->emailCentre = $emailCentre;

        return $this;
    }

    public function getTelephoneCentre(): ?int
    {
        return $this->telephoneCentre;
    }

    public function setTelephoneCentre(int $telephoneCentre): self
    {
        $this->telephoneCentre = $telephoneCentre;

        return $this;
    }

    public function getSiteWebCentre(): ?string
    {
        return $this->siteWebCentre;
    }

    public function setSiteWebCentre(?string $siteWebCentre): self
    {
        $this->siteWebCentre = $siteWebCentre;

        return $this;
    }
}

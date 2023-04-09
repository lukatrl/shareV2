<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichierRepository::class)]
class Fichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomOriginal = null;

    #[ORM\Column(length: 255)]
    private ?string $nomServeur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnvoi = null;

    #[ORM\Column(length: 3)]
    private ?string $extension = null;

    #[ORM\Column]
    private ?float $taille = null;

    #[ORM\ManyToOne(inversedBy: 'fichiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $proprietaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOriginal(): ?string
    {
        return $this->nomOriginal;
    }

    public function setNomOriginal(string $nomOriginal): self
    {
        $this->nomOriginal = $nomOriginal;

        return $this;
    }

    public function getNomServeur(): ?string
    {
        return $this->nomServeur;
    }

    public function setNomServeur(string $nomServeur): self
    {
        $this->nomServeur = $nomServeur;

        return $this;
    }

    public function getDateEnvoi(): ?\DateTimeInterface
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeInterface $dateEnvoi): self
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getProprietaire(): ?User
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?User $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }
}

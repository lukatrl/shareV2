<?php

namespace App\Entity;

use App\Repository\TagFichierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagFichierRepository::class)]
class TagFichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tagFichiers')]
    private ?Tag $tag = null;

    #[ORM\ManyToOne(inversedBy: 'tagFichiers')]
    private ?Fichier $Fichier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getFichier(): ?Fichier
    {
        return $this->Fichier;
    }

    public function setFichier(?Fichier $Fichier): self
    {
        $this->Fichier = $Fichier;

        return $this;
    }
}

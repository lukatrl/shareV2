<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    private ?string $couleur = null;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagFichier::class)]
    private Collection $tagFichiers;

    public function __construct()
    {
        $this->tagFichiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection<int, TagFichier>
     */
    public function getTagFichiers(): Collection
    {
        return $this->tagFichiers;
    }

    public function addTagFichier(TagFichier $tagFichier): self
    {
        if (!$this->tagFichiers->contains($tagFichier)) {
            $this->tagFichiers->add($tagFichier);
            $tagFichier->setTag($this);
        }

        return $this;
    }

    public function removeTagFichier(TagFichier $tagFichier): self
    {
        if ($this->tagFichiers->removeElement($tagFichier)) {
            // set the owning side to null (unless already changed)
            if ($tagFichier->getTag() === $this) {
                $tagFichier->setTag(null);
            }
        }

        return $this;
    }
}

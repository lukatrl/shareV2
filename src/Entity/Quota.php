<?php

namespace App\Entity;

use App\Repository\QuotaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuotaRepository::class)]
class Quota
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'quota', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $quotaMax = null;

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

    public function getQuotaMax(): ?int
    {
        return $this->quotaMax;
    }

    public function setQuotaMax(?int $quotaMax): self
    {
        $this->quotaMax = $quotaMax;

        return $this;
    }
}

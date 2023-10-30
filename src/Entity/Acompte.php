<?php

namespace App\Entity;

use App\Repository\AcompteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcompteRepository::class)]
class Acompte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'acompte', cascade: ['persist', 'remove'])]
    private ?Devis $devis = null;

    #[ORM\Column(nullable: true)]
    private ?float $Montant = null;

    #[ORM\ManyToOne(inversedBy: 'acomptes')]
    private ?ModeReglement $ModeReglement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(?float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getModeReglement(): ?ModeReglement
    {
        return $this->ModeReglement;
    }

    public function setModeReglement(?ModeReglement $ModeReglement): self
    {
        $this->ModeReglement = $ModeReglement;

        return $this;
    }
}

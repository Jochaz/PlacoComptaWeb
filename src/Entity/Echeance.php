<?php

namespace App\Entity;

use App\Repository\EcheanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcheanceRepository::class)]
class Echeance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'echeances')]
    private ?Facture $Facture = null;

    #[ORM\ManyToOne(inversedBy: 'echeances')]
    private ?ModeReglement $ModeReglement = null;

    #[ORM\Column(nullable: true)]
    private ?float $Montant = null;

    #[ORM\Column]
    private ?bool $isRegle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacture(): ?Facture
    {
        return $this->Facture;
    }

    public function setFacture(?Facture $Facture): self
    {
        $this->Facture = $Facture;

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

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(?float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function isIsRegle(): ?bool
    {
        return $this->isRegle;
    }

    public function setIsRegle(bool $isRegle): self
    {
        $this->isRegle = $isRegle;

        return $this;
    }
}

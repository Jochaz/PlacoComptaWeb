<?php

namespace App\Entity;

use App\Repository\LigneFactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFactureRepository::class)]
class LigneFacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'LigneFacture')]
    private ?Facture $facture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Designation = null;

    #[ORM\Column(nullable: true)]
    private ?float $PrixUnitaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $Qte = null;

    #[ORM\Column(nullable: true)]
    private ?float $Remise = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFactures', cascade:["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TVA $TVA = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFactures', cascade:["persist"])]
    private ?Materiaux $Materiaux = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->Designation;
    }

    public function setDesignation(?string $Designation): self
    {
        $this->Designation = $Designation;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->PrixUnitaire;
    }

    public function setPrixUnitaire(?float $PrixUnitaire): self
    {
        $this->PrixUnitaire = $PrixUnitaire;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->Qte;
    }

    public function setQte(?int $Qte): self
    {
        $this->Qte = $Qte;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->Remise;
    }

    public function setRemise(?float $Remise): self
    {
        $this->Remise = $Remise;

        return $this;
    }

    public function getTVA(): ?TVA
    {
        return $this->TVA;
    }

    public function setTVA(?TVA $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    public function getMateriaux(): ?Materiaux
    {
        return $this->Materiaux;
    }

    public function setMateriaux(?Materiaux $Materiaux): self
    {
        $this->Materiaux = $Materiaux;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\LigneDevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneDevisRepository::class)]
class LigneDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Designation = null;

    #[ORM\Column(nullable: true)]
    private ?int $PrixUnitaire = null;

    #[ORM\Column(nullable: true)]
    private ?int $Qte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?int $Remise = null;

    #[ORM\ManyToOne(inversedBy: 'ligneDevis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TVA $TVA = null;

    #[ORM\ManyToOne(inversedBy: 'ligneDevis')]
    private ?Devis $Devis = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrixUnitaire(): ?int
    {
        return $this->PrixUnitaire;
    }

    public function setPrixUnitaire(?int $PrixUnitaire): self
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

    public function getRemise(): ?int
    {
        return $this->Remise;
    }

    public function setRemise(?int $Remise): self
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

    public function getDevis(): ?Devis
    {
        return $this->Devis;
    }

    public function setDevis(?Devis $Devis): self
    {
        $this->Devis = $Devis;

        return $this;
    }
}

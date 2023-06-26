<?php

namespace App\Entity;

use App\Repository\ParametrageDevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametrageDevisRepository::class)]
class ParametrageDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Prefixe = null;

    #[ORM\Column]
    private ?bool $AnneeEnCours = null;

    #[ORM\Column]
    private ?int $NumeroAGenerer = null;

    #[ORM\Column]
    private ?int $NombreCaractereTotal = null;

    #[ORM\Column]
    private ?bool $CompletionAvecZero = null;

    #[ORM\Column(length: 255)]
    private ?string $TypeDocument = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefixe(): ?string
    {
        return $this->Prefixe;
    }

    public function setPrefixe(string $Prefixe): self
    {
        $this->Prefixe = $Prefixe;

        return $this;
    }

    public function isAnneeEnCours(): ?bool
    {
        return $this->AnneeEnCours;
    }

    public function setAnneeEnCours(bool $AnneeEnCours): self
    {
        $this->AnneeEnCours = $AnneeEnCours;

        return $this;
    }

    public function getNumeroAGenerer(): ?int
    {
        return $this->NumeroAGenerer;
    }

    public function setNumeroAGenerer(int $NumeroAGenerer): self
    {
        $this->NumeroAGenerer = $NumeroAGenerer;

        return $this;
    }

    public function getNombreCaractereTotal(): ?int
    {
        return $this->NombreCaractereTotal;
    }

    public function setNombreCaractereTotal(int $NombreCaractereTotal): self
    {
        $this->NombreCaractereTotal = $NombreCaractereTotal;

        return $this;
    }

    public function isCompletionAvecZero(): ?bool
    {
        return $this->CompletionAvecZero;
    }

    public function setCompletionAvecZero(bool $CompletionAvecZero): self
    {
        $this->CompletionAvecZero = $CompletionAvecZero;

        return $this;
    }

    public function getTypeDocument(): ?string
    {
        return $this->TypeDocument;
    }

    public function setTypeDocument(string $TypeDocument): self
    {
        $this->TypeDocument = $TypeDocument;

        return $this;
    }
}

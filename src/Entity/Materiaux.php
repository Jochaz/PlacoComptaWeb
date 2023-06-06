<?php

namespace App\Entity;

use App\Repository\MateriauxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MateriauxRepository::class)]
class Materiaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Designation = null;

    #[ORM\Column(nullable: true)]
    private ?float $PrixAchat = null;

    #[ORM\Column]
    private ?float $PrixUnitaire = null;

    #[ORM\ManyToOne(inversedBy: 'materiauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TVA $TVA = null;

    #[ORM\ManyToOne(inversedBy: 'materiauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieMateriaux $Categorie = null;

    #[ORM\ManyToOne(inversedBy: 'materiauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UniteMesure $UniteMesure = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->Designation;
    }

    public function setDesignation(string $Designation): self
    {
        $this->Designation = $Designation;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->PrixAchat;
    }

    public function setPrixAchat(?float $PrixAchat): self
    {
        $this->PrixAchat = $PrixAchat;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->PrixUnitaire;
    }

    public function setPrixUnitaire(float $PrixUnitaire): self
    {
        $this->PrixUnitaire = $PrixUnitaire;

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

    public function getCategorie(): ?CategorieMateriaux
    {
        return $this->Categorie;
    }

    public function setCategorie(?CategorieMateriaux $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getUniteMesure(): ?UniteMesure
    {
        return $this->UniteMesure;
    }

    public function setUniteMesure(?UniteMesure $UniteMesure): self
    {
        $this->UniteMesure = $UniteMesure;

        return $this;
    }
}

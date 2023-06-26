<?php

namespace App\Entity;

use App\Repository\EnteteDocumentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnteteDocumentRepository::class)]
class EnteteDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Ligne1Gauche = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne2Gauche = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne3Gauche = null;

    #[ORM\Column(length: 255)]
    private ?string $Ligne1Droite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne2Droite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne3Droite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne4Gauche = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne4Droite = null;

    #[ORM\Column(length: 255)]
    private ?string $VilleFaitA = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $NumeroTelFixe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $NumeroFax = null;

    #[ORM\Column(length: 255)]
    private ?string $NumeroTelPortable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigne1Gauche(): ?string
    {
        return $this->Ligne1Gauche;
    }

    public function setLigne1Gauche(string $Ligne1Gauche): self
    {
        $this->Ligne1Gauche = $Ligne1Gauche;

        return $this;
    }

    public function getLigne2Gauche(): ?string
    {
        return $this->Ligne2Gauche;
    }

    public function setLigne2Gauche(?string $Ligne2Gauche): self
    {
        $this->Ligne2Gauche = $Ligne2Gauche;

        return $this;
    }

    public function getLigne3Gauche(): ?string
    {
        return $this->Ligne3Gauche;
    }

    public function setLigne3Gauche(?string $Ligne3Gauche): self
    {
        $this->Ligne3Gauche = $Ligne3Gauche;

        return $this;
    }

    public function getLigne1Droite(): ?string
    {
        return $this->Ligne1Droite;
    }

    public function setLigne1Droite(string $Ligne1Droite): self
    {
        $this->Ligne1Droite = $Ligne1Droite;

        return $this;
    }

    public function getLigne2Droite(): ?string
    {
        return $this->Ligne2Droite;
    }

    public function setLigne2Droite(?string $Ligne2Droite): self
    {
        $this->Ligne2Droite = $Ligne2Droite;

        return $this;
    }

    public function getLigne3Droite(): ?string
    {
        return $this->Ligne3Droite;
    }

    public function setLigne3Droite(?string $Ligne3Droite): self
    {
        $this->Ligne3Droite = $Ligne3Droite;

        return $this;
    }

    public function getLigne4Gauche(): ?string
    {
        return $this->Ligne4Gauche;
    }

    public function setLigne4Gauche(?string $Ligne4Gauche): self
    {
        $this->Ligne4Gauche = $Ligne4Gauche;

        return $this;
    }

    public function getLigne4Droite(): ?string
    {
        return $this->Ligne4Droite;
    }

    public function setLigne4Droite(?string $Ligne4Droite): self
    {
        $this->Ligne4Droite = $Ligne4Droite;

        return $this;
    }

    public function getVilleFaitA(): ?string
    {
        return $this->VilleFaitA;
    }

    public function setVilleFaitA(string $VilleFaitA): self
    {
        $this->VilleFaitA = $VilleFaitA;

        return $this;
    }

    public function getNumeroTelFixe(): ?string
    {
        return $this->NumeroTelFixe;
    }

    public function setNumeroTelFixe(?string $NumeroTelFixe): self
    {
        $this->NumeroTelFixe = $NumeroTelFixe;

        return $this;
    }

    public function getNumeroFax(): ?string
    {
        return $this->NumeroFax;
    }

    public function setNumeroFax(?string $NumeroFax): self
    {
        $this->NumeroFax = $NumeroFax;

        return $this;
    }

    public function getNumeroTelPortable(): ?string
    {
        return $this->NumeroTelPortable;
    }

    public function setNumeroTelPortable(string $NumeroTelPortable): self
    {
        $this->NumeroTelPortable = $NumeroTelPortable;

        return $this;
    }
}

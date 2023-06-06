<?php

namespace App\Entity;

use App\Repository\UniteMesureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteMesureRepository::class)]
class UniteMesure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $Abreviation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getAbreviation(): ?string
    {
        return $this->Abreviation;
    }

    public function setAbreviation(string $Abreviation): self
    {
        $this->Abreviation = $Abreviation;

        return $this;
    }
}

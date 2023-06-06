<?php

namespace App\Entity;

use App\Repository\CategorieMateriauxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieMateriauxRepository::class)]
class CategorieMateriaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\ManyToOne(inversedBy: 'categorieMateriauxes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TVA $TVADefaut = null;

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

    public function getTVADefaut(): ?TVA
    {
        return $this->TVADefaut;
    }

    public function setTVADefaut(?TVA $TVADefaut): self
    {
        $this->TVADefaut = $TVADefaut;

        return $this;
    }
}

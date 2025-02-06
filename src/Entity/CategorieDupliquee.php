<?php

namespace App\Entity;

use App\Repository\CategorieDupliqueeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieDupliqueeRepository::class)]
class CategorieDupliquee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\ManyToOne(inversedBy: 'categorieDupliquees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieMateriaux $Categorie = null;

    #[ORM\ManyToOne(inversedBy: 'CategorieDupliquee')]
    private ?Devis $devis = null;

    #[ORM\ManyToOne(inversedBy: 'CategorieDupliquee')]
    private ?Facture $facture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getCategorie(): ?CategorieMateriaux
    {
        return $this->Categorie;
    }

    public function setCategorie(?CategorieMateriaux $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): static
    {
        $this->devis = $devis;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }
}

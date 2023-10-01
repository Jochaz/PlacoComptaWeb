<?php

namespace App\Entity;

use App\Repository\MateriauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(nullable: true)]
    private ?bool $Plus_utilise = null;

    #[ORM\ManyToMany(targetEntity: ModelePiece::class, mappedBy: 'Materiaux')]
    #[ORM\OrderBy(["Libelle" => "ASC"])]
    private Collection $modelePieces;

    #[ORM\OneToMany(mappedBy: 'Materiaux', targetEntity: LigneDevis::class)]
    private Collection $ligneDevis;

    #[ORM\OneToMany(mappedBy: 'Materiaux', targetEntity: LigneFacture::class)]
    private Collection $ligneFactures;

    public function __construct()
    {
        $this->modelePieces = new ArrayCollection();
        $this->ligneDevis = new ArrayCollection();
        $this->ligneFactures = new ArrayCollection();
    }

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

    public function isPlusUtilise(): ?bool
    {
        return $this->Plus_utilise;
    }

    public function setPlusUtilise(?bool $Plus_utilise): self
    {
        $this->Plus_utilise = $Plus_utilise;

        return $this;
    }

    /**
     * @return Collection<int, ModelePiece>
     */
    public function getModelePieces(): Collection
    {
        return $this->modelePieces;
    }

    public function addModelePiece(ModelePiece $modelePiece): self
    {
        if (!$this->modelePieces->contains($modelePiece)) {
            $this->modelePieces->add($modelePiece);
            $modelePiece->addMateriaux($this);
        }

        return $this;
    }

    public function removeModelePiece(ModelePiece $modelePiece): self
    {
        if ($this->modelePieces->removeElement($modelePiece)) {
            $modelePiece->removeMateriaux($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->Designation;
    }

    /**
     * @return Collection<int, LigneDevis>
     */
    public function getLigneDevis(): Collection
    {
        return $this->ligneDevis;
    }

    public function addLigneDevi(LigneDevis $ligneDevi): self
    {
        if (!$this->ligneDevis->contains($ligneDevi)) {
            $this->ligneDevis->add($ligneDevi);
            $ligneDevi->setMateriaux($this);
        }

        return $this;
    }

    public function removeLigneDevi(LigneDevis $ligneDevi): self
    {
        if ($this->ligneDevis->removeElement($ligneDevi)) {
            // set the owning side to null (unless already changed)
            if ($ligneDevi->getMateriaux() === $this) {
                $ligneDevi->setMateriaux(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneFacture>
     */
    public function getLigneFactures(): Collection
    {
        return $this->ligneFactures;
    }

    public function addLigneFacture(LigneFacture $ligneFacture): self
    {
        if (!$this->ligneFactures->contains($ligneFacture)) {
            $this->ligneFactures->add($ligneFacture);
            $ligneFacture->setMateriaux($this);
        }

        return $this;
    }

    public function removeLigneFacture(LigneFacture $ligneFacture): self
    {
        if ($this->ligneFactures->removeElement($ligneFacture)) {
            // set the owning side to null (unless already changed)
            if ($ligneFacture->getMateriaux() === $this) {
                $ligneFacture->setMateriaux(null);
            }
        }

        return $this;
    }
}

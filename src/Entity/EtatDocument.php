<?php

namespace App\Entity;

use App\Repository\EtatDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatDocumentRepository::class)]
class EtatDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Abrege = null;

    #[ORM\OneToMany(mappedBy: 'EtatDocument', targetEntity: Devis::class)]
    private Collection $devis;

    #[ORM\Column]
    private ?int $NumOrdre = null;

    #[ORM\Column(length: 10)]
    private ?string $TypeDocument = null;

    #[ORM\Column(length: 255)]
    private ?string $Badge = null;

    #[ORM\OneToMany(mappedBy: 'EtatDocument', targetEntity: Facture::class)]
    private Collection $factures;
    
    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

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

    public function getAbrege(): ?string
    {
        return $this->Abrege;
    }

    public function setAbrege(string $Abrege): static
    {
        $this->Abrege = $Abrege;

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): static
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setEtatDocument($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): static
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getEtatDocument() === $this) {
                $devi->setEtatDocument(null);
            }
        }

        return $this;
    }

    public function getNumOrdre(): ?int
    {
        return $this->NumOrdre;
    }

    public function setNumOrdre(int $NumOrdre): static
    {
        $this->NumOrdre = $NumOrdre;

        return $this;
    }

    public function getTypeDocument(): ?string
    {
        return $this->TypeDocument;
    }

    public function setTypeDocument(string $TypeDocument): static
    {
        $this->TypeDocument = $TypeDocument;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->Badge;
    }

    public function setBadge(string $Badge): static
    {
        $this->Badge = $Badge;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setEtatDocument($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getEtatDocument() === $this) {
                $facture->setEtatDocument(null);
            }
        }

        return $this;
    }
}

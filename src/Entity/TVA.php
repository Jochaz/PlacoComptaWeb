<?php

namespace App\Entity;

use App\Repository\TVARepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TVARepository::class)]
class TVA
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateFin = null;

    #[ORM\Column]
    private ?float $Taux = null;

    #[ORM\OneToMany(mappedBy: 'TVADefaut', targetEntity: CategorieMateriaux::class)]
    private Collection $categorieMateriauxes;

    #[ORM\OneToMany(mappedBy: 'TVA', targetEntity: Materiaux::class)]
    private Collection $materiauxes;

    #[ORM\OneToMany(mappedBy: 'TVA', targetEntity: LigneDevis::class)]
    private Collection $ligneDevis;

    public function __construct()
    {
        $this->categorieMateriauxes = new ArrayCollection();
        $this->materiauxes = new ArrayCollection();
        $this->ligneDevis = new ArrayCollection();
    }

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(?\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->Taux;
    }

    public function getLibelleTVAComplet(): ?string
    {
        return $this->Libelle.' ('.(string)$this->Taux.'%)';
    }

    public function setTaux(float $Taux): self
    {
        $this->Taux = $Taux;

        return $this;
    }

    /**
     * @return Collection<int, CategorieMateriaux>
     */
    public function getCategorieMateriauxes(): Collection
    {
        return $this->categorieMateriauxes;
    }

    public function addCategorieMateriaux(CategorieMateriaux $categorieMateriaux): self
    {
        if (!$this->categorieMateriauxes->contains($categorieMateriaux)) {
            $this->categorieMateriauxes->add($categorieMateriaux);
            $categorieMateriaux->setTVADefaut($this);
        }

        return $this;
    }

    public function removeCategorieMateriaux(CategorieMateriaux $categorieMateriaux): self
    {
        if ($this->categorieMateriauxes->removeElement($categorieMateriaux)) {
            // set the owning side to null (unless already changed)
            if ($categorieMateriaux->getTVADefaut() === $this) {
                $categorieMateriaux->setTVADefaut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Materiaux>
     */
    public function getMateriauxes(): Collection
    {
        return $this->materiauxes;
    }

    public function addMateriaux(Materiaux $materiaux): self
    {
        if (!$this->materiauxes->contains($materiaux)) {
            $this->materiauxes->add($materiaux);
            $materiaux->setTVA($this);
        }

        return $this;
    }

    public function removeMateriaux(Materiaux $materiaux): self
    {
        if ($this->materiauxes->removeElement($materiaux)) {
            // set the owning side to null (unless already changed)
            if ($materiaux->getTVA() === $this) {
                $materiaux->setTVA(null);
            }
        }

        return $this;
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
            $ligneDevi->setTVA($this);
        }

        return $this;
    }

    public function removeLigneDevi(LigneDevis $ligneDevi): self
    {
        if ($this->ligneDevis->removeElement($ligneDevi)) {
            // set the owning side to null (unless already changed)
            if ($ligneDevi->getTVA() === $this) {
                $ligneDevi->setTVA(null);
            }
        }

        return $this;
    }
}

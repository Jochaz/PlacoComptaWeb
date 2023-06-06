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

    public function __construct()
    {
        $this->categorieMateriauxes = new ArrayCollection();
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
}

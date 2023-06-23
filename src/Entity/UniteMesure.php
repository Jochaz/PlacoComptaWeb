<?php

namespace App\Entity;

use App\Repository\UniteMesureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'UniteMesure', targetEntity: Materiaux::class)]
    private Collection $materiauxes;

    #[ORM\Column(nullable: true)]
    private ?int $NumOrdre = null;

    #[ORM\Column(nullable: true)]
    private ?bool $plus_Utilise = null;

    public function __construct()
    {
        $this->materiauxes = new ArrayCollection();
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

    public function getAbreviation(): ?string
    {
        return $this->Abreviation;
    }

    public function setAbreviation(string $Abreviation): self
    {
        $this->Abreviation = $Abreviation;

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
            $materiaux->setUniteMesure($this);
        }

        return $this;
    }

    public function removeMateriaux(Materiaux $materiaux): self
    {
        if ($this->materiauxes->removeElement($materiaux)) {
            // set the owning side to null (unless already changed)
            if ($materiaux->getUniteMesure() === $this) {
                $materiaux->setUniteMesure(null);
            }
        }

        return $this;
    }

    public function getNumOrdre(): ?int
    {
        return $this->NumOrdre;
    }

    public function setNumOrdre(?int $NumOrdre): self
    {
        $this->NumOrdre = $NumOrdre;

        return $this;
    }

    public function isPlusUtilise(): ?bool
    {
        return $this->plus_Utilise;
    }

    public function setPlusUtilise(?bool $plus_Utilise): self
    {
        $this->plus_Utilise = $plus_Utilise;

        return $this;
    }
}

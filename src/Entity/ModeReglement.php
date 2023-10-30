<?php

namespace App\Entity;

use App\Repository\ModeReglementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModeReglementRepository::class)]
class ModeReglement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\OneToMany(mappedBy: 'ModeReglement', targetEntity: Echeance::class)]
    private Collection $echeances;

    #[ORM\OneToMany(mappedBy: 'ModeReglement', targetEntity: Acompte::class)]
    private Collection $acomptes;

    public function __construct()
    {
        $this->echeances = new ArrayCollection();
        $this->acomptes = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->getLibelle();
    }

    /**
     * @return Collection<int, Echeance>
     */
    public function getEcheances(): Collection
    {
        return $this->echeances;
    }

    public function addEcheance(Echeance $echeance): self
    {
        if (!$this->echeances->contains($echeance)) {
            $this->echeances->add($echeance);
            $echeance->setModeReglement($this);
        }

        return $this;
    }

    public function removeEcheance(Echeance $echeance): self
    {
        if ($this->echeances->removeElement($echeance)) {
            // set the owning side to null (unless already changed)
            if ($echeance->getModeReglement() === $this) {
                $echeance->setModeReglement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Acompte>
     */
    public function getAcomptes(): Collection
    {
        return $this->acomptes;
    }

    public function addAcompte(Acompte $acompte): self
    {
        if (!$this->acomptes->contains($acompte)) {
            $this->acomptes->add($acompte);
            $acompte->setModeReglement($this);
        }

        return $this;
    }

    public function removeAcompte(Acompte $acompte): self
    {
        if ($this->acomptes->removeElement($acompte)) {
            // set the owning side to null (unless already changed)
            if ($acompte->getModeReglement() === $this) {
                $acompte->setModeReglement(null);
            }
        }

        return $this;
    }
}

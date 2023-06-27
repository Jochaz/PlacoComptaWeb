<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Ligne1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ligne3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CP = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $BoitePostale = null;

    #[ORM\OneToMany(mappedBy: 'AdresseChantier', targetEntity: Devis::class)]
    private Collection $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLigne1(): ?string
    {
        return $this->Ligne1;
    }

    public function setLigne1(string $Ligne1): self
    {
        $this->Ligne1 = $Ligne1;

        return $this;
    }

    public function getLigne2(): ?string
    {
        return $this->Ligne2;
    }

    public function setLigne2(?string $Ligne2): self
    {
        $this->Ligne2 = $Ligne2;

        return $this;
    }

    public function getLigne3(): ?string
    {
        return $this->Ligne3;
    }

    public function setLigne3(?string $Ligne3): self
    {
        $this->Ligne3 = $Ligne3;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(?string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getCP(): ?string
    {
        return $this->CP;
    }

    public function setCP(?string $CP): self
    {
        $this->CP = $CP;

        return $this;
    }

    public function getBoitePostale(): ?string
    {
        return $this->BoitePostale;
    }

    public function setBoitePostale(?string $BoitePostale): self
    {
        $this->BoitePostale = $BoitePostale;

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setAdresseChantier($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getAdresseChantier() === $this) {
                $devi->setAdresseChantier(null);
            }
        }

        return $this;
    }
}

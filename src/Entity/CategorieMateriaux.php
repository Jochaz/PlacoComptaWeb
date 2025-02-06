<?php

namespace App\Entity;

use App\Repository\CategorieMateriauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'Categorie', targetEntity: Materiaux::class)]
    private Collection $materiauxes;

    #[ORM\Column(nullable: true)]
    private ?bool $plus_Utilise = null;

    #[ORM\OneToMany(mappedBy: 'Categorie', targetEntity: CategorieDupliquee::class, orphanRemoval: true)]
    private Collection $categorieDupliquees;

    public function __construct()
    {
        $this->materiauxes = new ArrayCollection();
        $this->categorieDupliquees = new ArrayCollection();
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

    public function getTVADefaut(): ?TVA
    {
        return $this->TVADefaut;
    }

    public function setTVADefaut(?TVA $TVADefaut): self
    {
        $this->TVADefaut = $TVADefaut;

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
            $materiaux->setCategorie($this);
        }

        return $this;
    }

    public function removeMateriaux(Materiaux $materiaux): self
    {
        if ($this->materiauxes->removeElement($materiaux)) {
            // set the owning side to null (unless already changed)
            if ($materiaux->getCategorie() === $this) {
                $materiaux->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->Libelle;
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

    /**
     * @return Collection<int, CategorieDupliquee>
     */
    public function getCategorieDupliquees(): Collection
    {
        return $this->categorieDupliquees;
    }

    public function addCategorieDupliquee(CategorieDupliquee $categorieDupliquee): static
    {
        if (!$this->categorieDupliquees->contains($categorieDupliquee)) {
            $this->categorieDupliquees->add($categorieDupliquee);
            $categorieDupliquee->setCategorie($this);
        }

        return $this;
    }

    public function removeCategorieDupliquee(CategorieDupliquee $categorieDupliquee): static
    {
        if ($this->categorieDupliquees->removeElement($categorieDupliquee)) {
            // set the owning side to null (unless already changed)
            if ($categorieDupliquee->getCategorie() === $this) {
                $categorieDupliquee->setCategorie(null);
            }
        }

        return $this;
    }
}

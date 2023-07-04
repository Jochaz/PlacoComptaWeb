<?php

namespace App\Entity;

use App\Repository\ModelePieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelePieceRepository::class)]
class ModelePiece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Libelle = null;

    #[ORM\Column]
    private ?bool $Plus_utilise = null;

    #[ORM\ManyToMany(targetEntity: Materiaux::class, inversedBy: 'modelePieces')]
    private Collection $Materiaux;

    public function __construct()
    {
        $this->Materiaux = new ArrayCollection();
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

    public function isPlusUtilise(): ?bool
    {
        return $this->Plus_utilise;
    }

    public function setPlusUtilise(bool $Plus_utilise): self
    {
        $this->Plus_utilise = $Plus_utilise;

        return $this;
    }

    /**
     * @return Collection<int, Materiaux>
     */
    public function getMateriaux(): Collection
    {
        return $this->Materiaux;
    }

    public function addMateriaux(Materiaux $materiaux): self
    {
        if (!$this->Materiaux->contains($materiaux)) {
            $this->Materiaux->add($materiaux);
        }

        return $this;
    }

    public function removeMateriaux(Materiaux $materiaux): self
    {
        $this->Materiaux->removeElement($materiaux);

        return $this;
    }

    public function __toString()
    {
        return $this->Libelle;
    }
}

<?php

namespace App\Entity;

use App\Repository\ParticulierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticulierRepository::class)]
class Particulier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datenaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseemail1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseemail2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseemail3 = null;

    #[ORM\Column(length: 255)]
    private ?string $numerotelephoneportable1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numerotelephoneportable2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numerotelephonefixe1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numerotelephonefixe2 = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\OneToMany(mappedBy: 'Client', targetEntity: Devis::class)]
    private Collection $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getAdresseemail1(): ?string
    {
        return $this->adresseemail1;
    }

    public function setAdresseemail1(string $adresseemail1): self
    {
        $this->adresseemail1 = $adresseemail1;

        return $this;
    }

    public function getAdresseemail2(): ?string
    {
        return $this->adresseemail2;
    }

    public function setAdresseemail2(?string $adresseemail2): self
    {
        $this->adresseemail2 = $adresseemail2;

        return $this;
    }

    public function getAdresseemail3(): ?string
    {
        return $this->adresseemail3;
    }

    public function setAdresseemail3(?string $adresseemail3): self
    {
        $this->adresseemail3 = $adresseemail3;

        return $this;
    }

    public function getNumerotelephoneportable1(): ?string
    {
        return $this->numerotelephoneportable1;
    }

    public function setNumerotelephoneportable1(string $numerotelephoneportable1): self
    {
        $this->numerotelephoneportable1 = $numerotelephoneportable1;

        return $this;
    }

    public function getNumerotelephoneportable2(): ?string
    {
        return $this->numerotelephoneportable2;
    }

    public function setNumerotelephoneportable2(?string $numerotelephoneportable2): self
    {
        $this->numerotelephoneportable2 = $numerotelephoneportable2;

        return $this;
    }

    public function getNumerotelephonefixe1(): ?string
    {
        return $this->numerotelephonefixe1;
    }

    public function setNumerotelephonefixe1(?string $numerotelephonefixe1): self
    {
        $this->numerotelephonefixe1 = $numerotelephonefixe1;

        return $this;
    }

    public function getNumerotelephonefixe2(): ?string
    {
        return $this->numerotelephonefixe2;
    }

    public function setNumerotelephonefixe2(?string $numerotelephonefixe2): self
    {
        $this->numerotelephonefixe2 = $numerotelephonefixe2;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

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
            $devi->setParticulier($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getParticulier() === $this) {
                $devi->setParticulier(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNom().' '.$this->getPrenom();
    }
}

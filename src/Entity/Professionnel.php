<?php

namespace App\Entity;

use App\Repository\ProfessionnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionnelRepository::class)]
class Professionnel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomsociete = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TVAIntra = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $SIRET = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $SIREN = null;

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

    #[ORM\OneToMany(mappedBy: 'Professionnel', targetEntity: Devis::class)]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'Professionnel', targetEntity: Facture::class)]
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

    public function getNomsociete(): ?string
    {
        return $this->nomsociete;
    }

    public function setNomsociete(string $nomsociete): self
    {
        $this->nomsociete = $nomsociete;

        return $this;
    }

    public function getTVAIntra(): ?string
    {
        return $this->TVAIntra;
    }

    public function setTVAIntra(?string $TVAIntra): self
    {
        $this->TVAIntra = $TVAIntra;

        return $this;
    }

    public function getSIRET(): ?string
    {
        return $this->SIRET;
    }

    public function setSIRET(?string $SIRET): self
    {
        $this->SIRET = $SIRET;

        return $this;
    }

    public function getSIREN(): ?string
    {
        return $this->SIREN;
    }

    public function setSIREN(?string $SIREN): self
    {
        $this->SIREN = $SIREN;

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

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
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
            $devi->setProfessionnel($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getProfessionnel() === $this) {
                $devi->setProfessionnel(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNomSociete();
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setProfessionnel($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getProfessionnel() === $this) {
                $facture->setProfessionnel(null);
            }
        }

        return $this;
    }
}

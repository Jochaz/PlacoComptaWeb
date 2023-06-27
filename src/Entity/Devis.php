<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NumDevis = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateDevis = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateSignature = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $NumDossier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Objet = null;

    #[ORM\Column]
    private ?int $Remise = null;

    #[ORM\Column]
    private ?int $PrixHT = null;

    #[ORM\Column]
    private ?int $PrixTTC = null;

    #[ORM\Column(nullable: true)]
    private ?bool $TVAAutoliquidation = null;

    #[ORM\Column]
    private ?bool $Plusutilise = null;

    #[ORM\OneToMany(mappedBy: 'Devis', targetEntity: LigneDevis::class)]
    private Collection $ligneDevis;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?Particulier $Client = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?Professionnel $Professionnel = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ModeReglement $ModeReglement = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?Adresse $AdresseChantier = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?Adresse $AdresseFacturation = null;

    public function __construct()
    {
        $this->ligneDevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumDevis(): ?string
    {
        return $this->NumDevis;
    }

    public function setNumDevis(string $NumDevis): self
    {
        $this->NumDevis = $NumDevis;

        return $this;
    }

    public function getDateDevis(): ?\DateTimeInterface
    {
        return $this->DateDevis;
    }

    public function setDateDevis(?\DateTimeInterface $DateDevis): self
    {
        $this->DateDevis = $DateDevis;

        return $this;
    }

    public function getDateSignature(): ?\DateTimeInterface
    {
        return $this->DateSignature;
    }

    public function setDateSignature(?\DateTimeInterface $DateSignature): self
    {
        $this->DateSignature = $DateSignature;

        return $this;
    }

    public function getNumDossier(): ?string
    {
        return $this->NumDossier;
    }

    public function setNumDossier(?string $NumDossier): self
    {
        $this->NumDossier = $NumDossier;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->Objet;
    }

    public function setObjet(?string $Objet): self
    {
        $this->Objet = $Objet;

        return $this;
    }

    public function getRemise(): ?int
    {
        return $this->Remise;
    }

    public function setRemise(int $Remise): self
    {
        $this->Remise = $Remise;

        return $this;
    }

    public function getPrixHT(): ?int
    {
        return $this->PrixHT;
    }

    public function setPrixHT(int $PrixHT): self
    {
        $this->PrixHT = $PrixHT;

        return $this;
    }

    public function getPrixTTC(): ?int
    {
        return $this->PrixTTC;
    }

    public function setPrixTTC(int $PrixTTC): self
    {
        $this->PrixTTC = $PrixTTC;

        return $this;
    }

    public function isTVAAutoliquidation(): ?bool
    {
        return $this->TVAAutoliquidation;
    }

    public function setTVAAutoliquidation(?bool $TVAAutoliquidation): self
    {
        $this->TVAAutoliquidation = $TVAAutoliquidation;

        return $this;
    }

    public function isPlusutilise(): ?bool
    {
        return $this->Plusutilise;
    }

    public function setPlusutilise(bool $Plusutilise): self
    {
        $this->Plusutilise = $Plusutilise;

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
            $ligneDevi->setDevis($this);
        }

        return $this;
    }

    public function removeLigneDevi(LigneDevis $ligneDevi): self
    {
        if ($this->ligneDevis->removeElement($ligneDevi)) {
            // set the owning side to null (unless already changed)
            if ($ligneDevi->getDevis() === $this) {
                $ligneDevi->setDevis(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Particulier
    {
        return $this->Client;
    }

    public function setClient(?Particulier $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getProfessionnel(): ?Professionnel
    {
        return $this->Professionnel;
    }

    public function setProfessionnel(?Professionnel $Professionnel): self
    {
        $this->Professionnel = $Professionnel;

        return $this;
    }

    public function getModeReglement(): ?ModeReglement
    {
        return $this->ModeReglement;
    }

    public function setModeReglement(?ModeReglement $ModeReglement): self
    {
        $this->ModeReglement = $ModeReglement;

        return $this;
    }

    public function getAdresseChantier(): ?Adresse
    {
        return $this->AdresseChantier;
    }

    public function setAdresseChantier(?Adresse $AdresseChantier): self
    {
        $this->AdresseChantier = $AdresseChantier;

        return $this;
    }

    public function getAdresseFacturation(): ?Adresse
    {
        return $this->AdresseFacturation;
    }

    public function setAdresseFacturation(?Adresse $AdresseFacturation): self
    {
        $this->AdresseFacturation = $AdresseFacturation;

        return $this;
    }
}

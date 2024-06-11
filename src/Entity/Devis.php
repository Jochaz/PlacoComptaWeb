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

    #[ORM\Column(nullable: true)]
    private ?float $Remise = null;

    #[ORM\Column(nullable: true)]
    private ?float $PrixHT = null;

    #[ORM\Column(nullable: true)]
    private ?float $PrixTTC = null;

    #[ORM\Column(nullable: true)]
    private ?bool $TVAAutoliquidation = null;

    #[ORM\Column]
    private ?bool $Plusutilise = null;

    #[ORM\OneToMany(mappedBy: 'Devis', targetEntity: LigneDevis::class, cascade:["persist"])]
    private Collection $ligneDevis;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?Particulier $Particulier = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?Professionnel $Professionnel = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?AdresseDocument $AdresseChantier = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?AdresseFacturation $AdresseFacturation = null;

    #[ORM\OneToOne(mappedBy: 'Devis', cascade: ['persist', 'remove'])]
    private ?Facture $facture = null;

    #[ORM\OneToOne(mappedBy: 'devis', cascade: ['persist', 'remove'])]
    private ?Acompte $acompte = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EtatDocument $EtatDocument = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $CreatedBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mailSend = null;

    public function __construct()
    {
        $this->ligneDevis = new ArrayCollection();
    }
    
    public function AddressChantierIsFacturation(): bool
    {
        if (!$this->getAdresseFacturation()){
            return false;
        }

        if (!$this->getAdresseChantier()){
            return false;
        }

        if ($this->getAdresseChantier()->getLigne1() != $this->getAdresseFacturation()->getLigne1()){
            return false;
        }
        if ($this->getAdresseChantier()->getLigne2() != $this->getAdresseFacturation()->getLigne2()){
            return false;
        }
        if ($this->getAdresseChantier()->getLigne3() != $this->getAdresseFacturation()->getLigne3()){
            return false;
        }
        if ($this->getAdresseChantier()->getVille() != $this->getAdresseFacturation()->getVille()){
            return false;
        }
        if ($this->getAdresseChantier()->getCP() != $this->getAdresseFacturation()->getCP()){
            return false;
        }
        if ($this->getAdresseChantier()->getBoitePostale() != $this->getAdresseFacturation()->getBoitePostale()){
            return false;
        }

        return true;
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

    public function getRemise(): ?float
    {
        return $this->Remise;
    }

    public function setRemise(float $Remise): self
    {
        $this->Remise = $Remise;

        return $this;
    }

    public function getPrixHT(): ?float
    {
        $PrixHT = 0;
        foreach($this->getLigneDevis() as $key){
            if ($key->getPrixUnitaire() >= $key->getRemise()){
                $PrixHT = $PrixHT + ((($key->getQte() * $key->getPrixUnitaire())) - ($key->getRemise() * $key->getQte()));
            }
        }
        $PrixHT = $PrixHT - $this->getRemise(); 
        if ($PrixHT < 0){
            $PrixHT = 0;
        }

        $this->setPrixHT($PrixHT);
        return round($this->PrixHT, 2);
    }

    public function setPrixHT(float $PrixHT): self
    {
        $this->PrixHT = $PrixHT;

        return $this;
    }

    public function getPrixTTC(): ?float
    {
        $PrixTTC = 0;
        $Temp = 0;
        $LstMontantTVA = [];
        foreach($this->getLigneDevis() as $key) {
            $Temp = 0;
            if ($key->getPrixUnitaire() >= $key->getRemise()){
                $Temp = ($key->getPrixUnitaire() - $key->getRemise()) * $key->getQte();
            }

            if ($key->getTVA()){
                $bPasse = false;
                foreach($LstMontantTVA as $key2){
                    $MontantTVA = $key2[0];
                    if ($MontantTVA->getTVA()->getId() == $key->getTVA()->getId()){
                        $MontantTVA->setMontantTotale($MontantTVA->getMontantTotale() + (($Temp * (1 + ($MontantTVA->getTVA()->getTaux() / 100))) - $Temp));
                        $bPasse = true;
                    }

                }

                if ($bPasse == false){
                    $MontantTotale = new MontantTVA();
                    $MontantTotale->setMontantTotale(($Temp * (1 + ($key->getTVA()->getTaux() / 100))) - $Temp);
                    $MontantTotale->setTVA($key->getTVA());
                    array_push($LstMontantTVA, [$MontantTotale]);
                }

                $PrixTTC = $PrixTTC + $Temp;
            }
        }

        $PrixTTC = $PrixTTC - $this->getRemise();
        if ($PrixTTC < 0){
            $PrixTTC = 0;
        } else {
            foreach ($LstMontantTVA as $MontantTVA){
                $PrixTTC =  $PrixTTC + $MontantTVA[0]->getMontantTotale();
            }
        }
        $this->setPrixTTC($PrixTTC);

        return round($this->PrixTTC, 2);
    }

    public function setPrixTTC(float $PrixTTC): self
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

    public function getParticulier(): ?Particulier
    {
        return $this->Particulier;
    }

    public function setParticulier(?Particulier $Particulier): self
    {
        $this->Particulier = $Particulier;

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

    public function getAdresseChantier(): ?AdresseDocument
    {
        return $this->AdresseChantier;
    }

    public function setAdresseChantier(?AdresseDocument $AdresseChantier): self
    {
        $this->AdresseChantier = $AdresseChantier;

        return $this;
    }

    public function getAdresseFacturation(): ?AdresseFacturation
    {
        return $this->AdresseFacturation;
    }

    public function setAdresseFacturation(?AdresseFacturation $AdresseFacturation): self
    {
        $this->AdresseFacturation = $AdresseFacturation;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        // unset the owning side of the relation if necessary
        if ($facture === null && $this->facture !== null) {
            $this->facture->setDevis(null);
        }

        // set the owning side of the relation if necessary
        if ($facture !== null && $facture->getDevis() !== $this) {
            $facture->setDevis($this);
        }

        $this->facture = $facture;

        return $this;
    }

    public function getAcompte(): ?Acompte
    {
        return $this->acompte;
    }

    public function setAcompte(?Acompte $acompte): self
    {
        // unset the owning side of the relation if necessary
        if ($acompte === null && $this->acompte !== null) {
            $this->acompte->setDevis(null);
        }

        // set the owning side of the relation if necessary
        if ($acompte !== null && $acompte->getDevis() !== $this) {
            $acompte->setDevis($this);
        }

        $this->acompte = $acompte;

        return $this;
    }

    public function getEtatDocument(): ?EtatDocument
    {
        return $this->EtatDocument;
    }

    public function setEtatDocument(?EtatDocument $EtatDocument): static
    {
        $this->EtatDocument = $EtatDocument;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->CreatedBy;
    }

    public function setCreatedBy(?User $CreatedBy): static
    {
        $this->CreatedBy = $CreatedBy;

        return $this;
    }

    public function getMailSend(): ?\DateTimeInterface
    {
        return $this->mailSend;
    }

    public function setMailSend(?\DateTimeInterface $mailSend): static
    {
        $this->mailSend = $mailSend;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\PseudoTypes\True_;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NumFacture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateFacture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DatePaiementFacture = null;

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

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: LigneFacture::class, cascade:["persist"])]
    private Collection $LigneFacture;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Particulier $Particulier = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Professionnel $Professionnel = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?AdresseDocument $AdresseChantier = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?AdresseFacturation $AdresseFacturation = null;

    #[ORM\OneToOne(inversedBy: 'facture', cascade: ['persist', 'remove'])]
    private ?Devis $Devis = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isEditer = null;

    #[ORM\OneToMany(mappedBy: 'Facture', cascade:["persist"], targetEntity: Echeance::class)]
    private Collection $echeances;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?EtatDocument $EtatDocument = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $CreatedBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $mailSend = null;

    public function __construct()
    {
        $this->LigneFacture = new ArrayCollection();
        $this->setIsEditer(false);
        $this->echeances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumFacture(): ?string
    {
        return $this->NumFacture;
    }

    public function setNumFacture(string $NumFacture): self
    {
        $this->NumFacture = $NumFacture;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->DateFacture;
    }

    public function setDateFacture(?\DateTimeInterface $DateFacture): self
    {
        $this->DateFacture = $DateFacture;

        return $this;
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

    public function getDatePaiementFacture(): ?\DateTimeInterface
    {
        return $this->DatePaiementFacture;
    }

    public function setDatePaiementFacture(?\DateTimeInterface $DatePaiementFacture): self
    {
        $this->DatePaiementFacture = $DatePaiementFacture;

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

    public function setRemise(?float $Remise): self
    {
        $this->Remise = $Remise;

        return $this;
    }

    public function getPrixHT(): ?float
    {
        $PrixHT = 0;
        foreach($this->getLigneFacture() as $key){
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

    public function setPrixHT(?float $PrixHT): self
    {
        $this->PrixHT = $PrixHT;

        return $this;
    }

    public function getPrixTTC(): ?float
    {
        $PrixTTC = 0;
        $Temp = 0;
        $LstMontantTVA = [];
        foreach($this->getLigneFacture() as $key) {
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
     * @return Collection<int, LigneFacture>
     */
    public function getLigneFacture(): Collection
    {
        return $this->LigneFacture;
    }

    public function addLigneFacture(LigneFacture $ligneFacture): self
    {
        if (!$this->LigneFacture->contains($ligneFacture)) {
            $this->LigneFacture->add($ligneFacture);
            $ligneFacture->setFacture($this);
        }

        return $this;
    }

    public function removeLigneFacture(LigneFacture $ligneFacture): self
    {
        if ($this->LigneFacture->removeElement($ligneFacture)) {
            // set the owning side to null (unless already changed)
            if ($ligneFacture->getFacture() === $this) {
                $ligneFacture->setFacture(null);
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

    public function getDevis(): ?Devis
    {
        return $this->Devis;
    }

    public function setDevis(?Devis $Devis): self
    {
        $this->Devis = $Devis;

        return $this;
    }

    public function isIsEditer(): ?bool
    {
        return $this->isEditer;
    }

    public function setIsEditer(?bool $isEditer): self
    {
        $this->isEditer = $isEditer;

        return $this;
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
            $echeance->setFacture($this);
        }

        return $this;
    }

    public function removeEcheance(Echeance $echeance): self
    {
        if ($this->echeances->removeElement($echeance)) {
            // set the owning side to null (unless already changed)
            if ($echeance->getFacture() === $this) {
                $echeance->setFacture(null);
            }
        }

        return $this;
    }

    public function peutModifierDocument(): ?bool
    {
        $result = true;
        foreach ($this->echeances as $echeance){
            if ($echeance->isIsRegle()){
                $result = false;
                break;
            }
        }

        if ($result){
            $result = !$this->isEditer;
        }

        return $result;
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

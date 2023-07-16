<?php 

namespace App\Entity;

class MontantTVA{
    private TVA $TVA;
    private float $MontantTotale;

    public function getTVA(): ?TVA
    {
        return $this->TVA;
    }

    public function setTVA(?TVA $TVADefaut): self
    {
        $this->TVA = $TVADefaut;

        return $this;
    }

    public function getMontantTotale(): ?float
    {
        return $this->MontantTotale;
    }

    public function setMontantTotale(float $MontantTotale): self
    {
        $this->MontantTotale = $MontantTotale;

        return $this;
    }
} 
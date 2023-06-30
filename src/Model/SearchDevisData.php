<?php

namespace App\Model;

use App\Entity\Particulier;
use App\Entity\Professionnel;

class SearchDevisData{
    public $page = 1;

    public string $numDevis = '';

    public int $prixminTTC = 0;

    public int $prixmaxTTC = 0;

    public Particulier $particulier;

    public Professionnel $professionnel;

}
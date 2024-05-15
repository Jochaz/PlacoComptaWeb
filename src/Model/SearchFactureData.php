<?php

namespace App\Model;

class SearchFactureData{
    public $page = 1;

    public string $NumFacture = '';

    public int $prixminTTC = 0;

    public int $prixmaxTTC = 0;

    public string $client;
    
     /**
     * @var EtatDocument[]
     */
    public array $etatDocument = [];

}
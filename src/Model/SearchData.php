<?php

namespace App\Model;

use App\Entity\CategorieMateriaux;

class SearchData{
    public $page = 1;

    public string $libelle = '';

    public float $prixminachat = 0;

    public float $prixmaxachat = 0;

    public float $prixminunitaire = 0;

    public float $prixmaxunitaire = 0;

    /**
     * @var CategorieMateriaux[]
     */
    public array $categorie = [];
}
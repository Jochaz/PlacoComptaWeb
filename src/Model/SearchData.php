<?php

namespace App\Model;

use App\Entity\CategorieMateriaux;

class SearchData{
    public $page = 1;

    public string $libelle = '';

    /**
     * @var CategorieMateriaux[]
     */
    public array $categorie = [];
}
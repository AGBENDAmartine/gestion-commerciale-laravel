<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatistiquesExport implements WithMultipleSheets
{
    protected $bilan;
    protected $ca_par_mois;
    protected $top_produits;

    public function __construct($bilan, $ca_par_mois, $top_produits)
    {
        $this->bilan        = $bilan;
        $this->ca_par_mois  = $ca_par_mois;
        $this->top_produits = $top_produits;
    }

    public function sheets(): array
    {
        return [
            new BilanSheet($this->bilan),
            new CaMoisSheet($this->ca_par_mois),
            new TopProduitsSheet($this->top_produits),
        ];
    }
}
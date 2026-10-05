<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneVente extends Model
{
    protected $table      = 'lignes_vente';
    protected $primaryKey = 'id_ligne';

    protected $fillable = [
        'id_vente',
        'id_produit',
        'quantite',
        'prix_unitaire',
    ];

    // Relations
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'id_vente');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }

    // Calculer le sous-total
    public function getSousTotal(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }
}
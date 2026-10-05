<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneAchat extends Model
{
    protected $table      = 'lignes_achat';
    protected $primaryKey = 'id_ligne';

    protected $fillable = [
        'id_achat',
        'id_produit',
        'quantite',
        'prix_unitaire',
    ];

    // Relations
    public function achat()
    {
        return $this->belongsTo(Achat::class, 'id_achat');
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
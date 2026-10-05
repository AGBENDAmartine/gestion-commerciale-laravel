<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $table      = 'mouvements_stock';
    protected $primaryKey = 'id_mouvement';

    protected $fillable = [
        'type_mouvement',
        'quantite',
        'date_mouvement',
        'motif',
        'id_produit',
        'id_entrepot',
        'id_utilisateur',
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }

    public function entrepot()
    {
        return $this->belongsTo(Entrepot::class, 'id_entrepot');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
}
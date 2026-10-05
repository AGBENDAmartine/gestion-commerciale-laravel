<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    protected $table      = 'entrepots';
    protected $primaryKey = 'id_entrepot';

    protected $fillable = [
        'nom',
        'type',
        'adresse',
    ];

    // Relations
    public function produits()
    {
        return $this->belongsToMany(
            Produit::class,
            'stock_entrepot',
            'id_entrepot',
            'id_produit'
        )->withPivot('quantite');
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class, 'id_entrepot');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table      = 'produits';
    protected $primaryKey = 'id_produit';

    protected $fillable = [
        'reference',
        'marque',
        'type',
        'dimension',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'seuil_alerte',
    ];

    // Relations
    public function lignesVente()
    {
        return $this->hasMany(LigneVente::class, 'id_produit');
    }

    public function lignesAchat()
    {
        return $this->hasMany(LigneAchat::class, 'id_produit');
    }

    public function entrepots()
    {
        return $this->belongsToMany(
            Entrepot::class,
            'stock_entrepot',
            'id_produit',
            'id_entrepot'
        )->withPivot('quantite');
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class, 'id_produit');
    }

    // Vérifier si le produit est en rupture de stock
    public function enRuptureStock(): bool
    {
        return $this->quantite_stock <= $this->seuil_alerte;
    }

    // Calculer la marge
    public function getMarge(): float
    {
        return $this->prix_vente - $this->prix_achat;
    }
}
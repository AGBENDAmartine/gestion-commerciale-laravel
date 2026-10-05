<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table      = 'clients';
    protected $primaryKey = 'id_client';

    protected $fillable = [
        'nom',
        'telephone',
        'adresse',
        'remarques',
        'date_inscription',
        'categorie',
        'remise',
    ];

    // Relations
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_client');
    }

    // Obtenir la couleur du badge selon la catégorie
    public function getBadgeCategorie(): string
    {
        return match($this->categorie) {
            'vip'    => 'danger',
            'fidele' => 'warning',
            default  => 'secondary',
        };
    }

    // Obtenir l'icône selon la catégorie
    public function getIconeCategorie(): string
    {
        return match($this->categorie) {
            'vip'    => '⭐⭐⭐',
            'fidele' => '⭐⭐',
            default  => '⭐',
        };
    }
}
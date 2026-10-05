<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $table      = 'ventes';
    protected $primaryKey = 'id_vente';

    protected $fillable = [
        'date_vente',
        'montant_total',
        'id_client',
        'id_utilisateur',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function lignes()
    {
        return $this->hasMany(LigneVente::class, 'id_vente');
    }

    public function facture()
    {
        return $this->hasOne(Facture::class, 'id_vente');
    }
}
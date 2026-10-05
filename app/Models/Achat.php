<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    protected $table      = 'achats';
    protected $primaryKey = 'id_achat';

    protected $fillable = [
        'date_achat',
        'montant_total',
        'type_achat',
        'id_fournisseur',
        'id_utilisateur',
    ];

    // Relations
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function lignes()
    {
        return $this->hasMany(LigneAchat::class, 'id_achat');
    }
}
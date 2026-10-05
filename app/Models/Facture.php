<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $table      = 'factures';
    protected $primaryKey = 'id_facture';

    protected $fillable = [
        'numero',
        'date_facture',
        'montant_total',
        'statut',
        'id_vente',
    ];

    // Relations
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'id_vente');
    }

    // Générer automatiquement le numéro de facture
    public static function genererNumero(): string
    {
        $annee   = date('Y');
        $dernier = static::whereYear('date_facture', $annee)->count();
        return sprintf('FAC-%s-%05d', $annee, $dernier + 1);
    }
}
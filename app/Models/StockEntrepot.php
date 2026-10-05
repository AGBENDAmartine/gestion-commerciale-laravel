<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntrepot extends Model
{
    protected $table      = 'stock_entrepot';
    protected $primaryKey = 'id_stock';

    protected $fillable = [
        'id_produit',
        'id_entrepot',
        'quantite',
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
}
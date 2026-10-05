<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table      = 'fournisseurs';
    protected $primaryKey = 'id_fournisseur';

    protected $fillable = [
        'nom',
        'pays',
        'adresse',
        'telephone',
        'email',
    ];

    // Relations
    public function achats()
    {
        return $this->hasMany(Achat::class, 'id_fournisseur');
    }
}
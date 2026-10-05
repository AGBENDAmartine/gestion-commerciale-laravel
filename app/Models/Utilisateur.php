<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Utilisateur extends Authenticatable
{
    protected $table      = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'derniere_connexion',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    protected function casts(): array
    {
        return [
            'mot_de_passe' => 'hashed',
        ];
    }

    // Relations
    public function getAuthPassword()
{
    return $this->mot_de_passe;
}
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id_utilisateur');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class, 'id_utilisateur');
    }

    // Vérification des rôles
    public function isAdmin(): bool
    {
        return $this->role === 'administrateur';
    }

    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    public function isVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    public function isMagasinier(): bool
    {
        return $this->role === 'magasinier';
    }
}
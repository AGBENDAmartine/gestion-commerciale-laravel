<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id('id_utilisateur');
            $table->string('nom', 100);
            $table->string('email', 150)->unique();
            $table->string('mot_de_passe', 255);
            $table->enum('role', ['administrateur', 'gestionnaire', 'vendeur', 'magasinier']);
            $table->dateTime('derniere_connexion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
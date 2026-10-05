<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lignes_vente', function (Blueprint $table) {
            $table->id('id_ligne');
            $table->unsignedBigInteger('id_vente');
            $table->unsignedBigInteger('id_produit');
            $table->unsignedInteger('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->timestamps();

            $table->foreign('id_vente')
                  ->references('id_vente')
                  ->on('ventes')
                  ->onDelete('cascade');

            $table->foreign('id_produit')
                  ->references('id_produit')
                  ->on('produits')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_vente');
    }
};
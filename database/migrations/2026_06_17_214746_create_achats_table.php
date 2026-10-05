<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id('id_achat');
            $table->date('date_achat');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('type_achat', ['commande_fournisseur', 'achat_port']);
            $table->unsignedBigInteger('id_fournisseur')->nullable();
            $table->unsignedBigInteger('id_utilisateur');
            $table->timestamps();

            $table->foreign('id_fournisseur')
                  ->references('id_fournisseur')
                  ->on('fournisseurs')
                  ->onDelete('set null');

            $table->foreign('id_utilisateur')
                  ->references('id_utilisateur')
                  ->on('utilisateurs')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
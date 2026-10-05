<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id('id_vente');
            $table->date('date_vente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_utilisateur');
            $table->timestamps();

            $table->foreign('id_client')
                  ->references('id_client')
                  ->on('clients')
                  ->onDelete('restrict');

            $table->foreign('id_utilisateur')
                  ->references('id_utilisateur')
                  ->on('utilisateurs')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
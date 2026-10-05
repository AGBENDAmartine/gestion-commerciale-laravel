<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id('id_facture');
            $table->string('numero', 50)->unique();
            $table->date('date_facture');
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['emise', 'payee', 'annulee'])->default('emise');
            $table->unsignedBigInteger('id_vente')->unique();
            $table->timestamps();

            $table->foreign('id_vente')
                  ->references('id_vente')
                  ->on('ventes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_entrepot', function (Blueprint $table) {
            $table->id('id_stock');
            $table->unsignedBigInteger('id_produit');
            $table->unsignedBigInteger('id_entrepot');
            $table->unsignedInteger('quantite')->default(0);
            $table->timestamps();

            $table->unique(['id_produit', 'id_entrepot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_entrepot');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id('id_fournisseur');
            $table->string('nom', 150);
            $table->string('pays', 100);
            $table->string('adresse', 255)->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
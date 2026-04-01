<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('burgers', function (Blueprint $table) {
           $table->id();
            $table->string('nom');
            $table->double('prix', 8, 2); // Casté en float dans le modèle
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('archive')->default(false);
            $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burgers');
    }
};

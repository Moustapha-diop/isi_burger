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
        Schema::create('notifications', function (Blueprint $table) {
           $table->id();
            $table->foreignId('commande_id')->nullable()->constrained();
            $table->string('message');
            $table->string('type'); // 'CONFIRMATION', 'NOUVELLE_COMMANDE', 'FACTURE'
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ce champ est déjà défini dans create_users_table.
     * Cette migration est conservée pour compatibilité mais ne fait rien.
     */
    public function up(): void
    {
        // La colonne 'role' est déjà créée dans 2014_10_12_000000_create_users_table.php
    }

    public function down(): void
    {
        // Rien à annuler
    }
};

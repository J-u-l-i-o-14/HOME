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
        Schema::table('users', function (Blueprint $table) {
            // Supprimer l'ancienne colonne role ENUM
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Ajouter la nouvelle colonne role VARCHAR
            $table->string('role', 20)->default('donor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la nouvelle colonne role
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Remettre l'ancienne colonne role ENUM
            $table->enum('role', ['donneur', 'medecin', 'admin'])->default('donneur');
        });
    }
};

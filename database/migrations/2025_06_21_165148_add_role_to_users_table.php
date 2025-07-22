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
            $table->enum('role', ['donneur', 'medecin', 'admin'])->default('donneur');
            $table->string('phone')->nullable();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('last_donation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'blood_type', 'birth_date', 'address', 'gender', 'last_donation_date']);
        });
    }
};

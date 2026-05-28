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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titre');
            $table->enum('type', ['CV', 'Lettre de motivation', 'Diplôme', 'Certificat', 'CNI', 'CNPS', 'Contrat', 'Autre']);
            $table->string('fichier'); // Chemin vers le fichier
            $table->date('date_emission')->nullable();
            $table->date('date_expiration')->nullable();
            $table->text('description')->nullable();
            $table->boolean('confidentiel')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

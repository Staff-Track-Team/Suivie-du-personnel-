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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->enum('type', ['CDI', 'CDD', 'Stage', 'Alternance', 'Freelance']);
            $table->date('date_debut');
            $table->date('date_fin')->nullable(); // Null pour CDI
            $table->decimal('salaire_base', 10, 2);
            $table->string('poste');
            $table->text('description')->nullable();
            $table->enum('statut', ['Actif', 'Terminé', 'Suspendu'])->default('Actif');
            $table->string('fichier_contrat')->nullable(); // Chemin vers le PDF du contrat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

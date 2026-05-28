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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            $table->time('pause_debut')->nullable();
            $table->time('pause_fin')->nullable();
            $table->decimal('heures_travaillees', 4, 2)->default(0); // Calculées automatiquement
            $table->enum('statut', ['Présent', 'Absent', 'Retard', 'Congé', 'Maladie'])->default('Présent');
            $table->text('motif_absence')->nullable();
            $table->boolean('justifie')->default(false);
            $table->timestamps();
            
            // Un employé ne peut avoir qu'un seul enregistrement par jour
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

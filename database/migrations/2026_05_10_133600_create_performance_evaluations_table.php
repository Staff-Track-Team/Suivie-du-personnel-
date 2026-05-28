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
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->date('date_evaluation');
            $table->enum('periode', ['Mensuelle', 'Trimestrielle', 'Semestrielle', 'Annuelle']);
            $table->decimal('competences_techniques', 3, 2); // Note sur 5
            $table->decimal('qualite_travail', 3, 2);
            $table->decimal('productivite', 3, 2);
            $table->decimal('ponctualite', 3, 2);
            $table->decimal('discipline', 3, 2);
            $table->decimal('collaboration', 3, 2);
            $table->decimal('note_generale', 3, 2);
            $table->text('points_forts')->nullable();
            $table->text('axes_amelioration')->nullable();
            $table->text('commentaires')->nullable();
            $table->text('objectifs_prochain_periode')->nullable();
            $table->enum('statut', ['Brouillon', 'Validé', 'Archivé'])->default('Brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};

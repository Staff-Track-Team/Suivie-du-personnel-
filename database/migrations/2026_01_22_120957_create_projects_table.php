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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date'); // Obligatoire selon la logique projet
            $table->date('end_date');
            $table->enum('status', ['En attente', 'En cours', 'Terminé', 'Suspendu', 'Annulé'])->default('En attente');
            $table->enum('priority', ['Basse', 'Moyenne', 'Haute', 'Urgente'])->default('Moyenne');
            $table->decimal('budget', 15, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

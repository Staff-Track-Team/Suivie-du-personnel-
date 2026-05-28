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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->year('annee');
            $table->integer('conges_payes_total')->default(30); // 30 jours par an
            $table->integer('conges_payes_pris')->default(0);
            $table->integer('conges_payes_restants')->default(30);
            $table->integer('maladies_total')->default(15); // 15 jours par an
            $table->integer('maladies_pris')->default(0);
            $table->integer('maladies_restants')->default(15);
            $table->integer('exceptionnels_total')->default(5); // 5 jours par an
            $table->integer('exceptionnels_pris')->default(0);
            $table->integer('exceptionnels_restants')->default(5);
            $table->timestamps();
            
            // Un employé ne peut avoir qu'un seul solde par année
            $table->unique(['user_id', 'annee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};

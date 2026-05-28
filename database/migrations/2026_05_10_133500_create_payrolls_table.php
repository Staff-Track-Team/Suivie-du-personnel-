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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->decimal('salaire_base', 10, 2);
            $table->decimal('heures_supplementaires', 4, 2)->default(0);
            $table->decimal('taux_horaire_sup', 5, 2)->default(0);
            $table->decimal('montant_heures_sup', 8, 2)->default(0);
            $table->decimal('prime_anciennete', 8, 2)->default(0);
            $table->decimal('prime_responsabilite', 8, 2)->default(0);
            $table->decimal('prime_performance', 8, 2)->default(0);
            $table->decimal('autres_primes', 8, 2)->default(0);
            $table->decimal('salaire_brut', 10, 2);
            $table->decimal('cnps_employee', 8, 2)->default(0); // 2.8%
            $table->decimal('cnps_employer', 8, 2)->default(0); // 4.2%
            $table->decimal('impot_sur_salaire', 8, 2)->default(0);
            $table->decimal('autres_retentions', 8, 2)->default(0);
            $table->decimal('total_retentions', 8, 2)->default(0);
            $table->decimal('salaire_net', 10, 2);
            $table->date('date_paiement');
            $table->enum('statut', ['En cours', 'Payé', 'Annulé'])->default('En cours');
            $table->string('bulletin_pdf')->nullable(); // Chemin vers le PDF du bulletin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};

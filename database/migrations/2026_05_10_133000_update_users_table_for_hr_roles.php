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
            // Remplacer le champ is_admin par un système de rôles plus flexible
            $table->dropColumn('is_admin');
            
            // Ajouter le champ role avec les 3 rôles RH
            $table->enum('role', ['admin', 'manager_rh', 'employe'])->default('employe')->after('email');
            
            // Ajouter des champs spécifiques RH
            $table->string('numero_cni')->nullable()->after('phone');
            $table->string('numero_cnps')->nullable()->after('numero_cni');
            $table->string('situation_matrimoniale')->nullable()->after('numero_cnps');
            $table->integer('nombre_enfants')->default(0)->after('situation_matrimoniale');
            $table->string('niveau_education')->nullable()->after('nombre_enfants');
            $table->string('compte_bancaire')->nullable()->after('niveau_education');
            $table->string('nom_urgence')->nullable()->after('compte_bancaire');
            $table->string('contact_urgence')->nullable()->after('nom_urgence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'numero_cni',
                'numero_cnps',
                'situation_matrimoniale',
                'nombre_enfants',
                'niveau_education',
                'compte_bancaire',
                'nom_urgence',
                'contact_urgence'
            ]);
            
            $table->boolean('is_admin')->default(false);
        });
    }
};

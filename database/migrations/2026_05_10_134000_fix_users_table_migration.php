<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Vérifier si la colonne is_admin existe avant de la supprimer
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
            
            // Ajouter le champ role seulement s'il n'existe pas déjà
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'manager_rh', 'employe'])->default('employe')->after('email');
            }
            
            // Ajouter les champs RH seulement s'ils n'existent pas déjà
            $rhFields = [
                'numero_cni' => 'string',
                'numero_cnps' => 'string', 
                'situation_matrimoniale' => 'string',
                'nombre_enfants' => 'integer',
                'niveau_education' => 'string',
                'compte_bancaire' => 'string',
                'nom_urgence' => 'string',
                'contact_urgence' => 'string'
            ];
            
            foreach ($rhFields as $field => $type) {
                if (!Schema::hasColumn('users', $field)) {
                    if ($type === 'integer') {
                        $table->$type($field)->default(0)->nullable();
                    } else {
                        $table->$type($field)->nullable();
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remettre is_admin si nécessaire
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }
            
            // Supprimer les champs RH ajoutés
            $rhFields = [
                'role',
                'numero_cni',
                'numero_cnps',
                'situation_matrimoniale', 
                'nombre_enfants',
                'niveau_education',
                'compte_bancaire',
                'nom_urgence',
                'contact_urgence'
            ];
            
            foreach ($rhFields as $field) {
                if (Schema::hasColumn('users', $field)) {
                    $table->dropColumn($field);
                }
            }
        });
    }
};

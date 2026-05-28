<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EmployeeInfo;

class EmployeeInfoSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des informations employé pour les utilisateurs existants
        $users = User::where('role', '!=', 'admin')->get();
        
        foreach ($users as $user) {
            EmployeeInfo::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department' => match($user->role) {
                        'manager_rh' => 'Ressources Humaines',
                        'employe' => 'Informatique',
                        default => 'Général'
                    },
                    'position' => match($user->role) {
                        'manager_rh' => 'Manager RH',
                        'employe' => 'Développeur',
                        default => 'Employé'
                    },
                    'hired_at' => now()->subMonths(rand(1, 12)),
                    'address' => 'Adresse de ' . $user->name,
                    'city' => 'Douala',
                ]
            );
        }
    }
}

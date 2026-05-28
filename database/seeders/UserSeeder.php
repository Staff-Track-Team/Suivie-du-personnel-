<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creation des administrateurs systeme avec le nouveau rôle RH
        
        User::create([
            'matricule' => 'ADMSYS24092025165178976',
            'name' => 'Korusaki',
            'email' => 'korusakiroot@gmail.com',
            'role' => 'admin',
            'status' => 'Actif',
            'password' => Hash::make('Korusaki1234')
        ]);

        // Creation des utilisateurs RH de test
        User::create([
            'matricule' => 'MGR001',
            'name' => 'Manager RH Test',
            'email' => 'manager@example.com',
            'role' => 'manager_rh',
            'status' => 'Actif',
            'password' => Hash::make('password')
        ]);

        User::create([
            'matricule' => 'EMP001',
            'name' => 'Employé Test',
            'email' => 'employe@example.com',
            'role' => 'employe',
            'status' => 'Actif',
            'password' => Hash::make('password')
        ]);
    }
}

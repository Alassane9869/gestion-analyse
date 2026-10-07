<?php

namespace Database\Seeders;

use App\Models\Medecin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MedecinTestSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Aminata Traoré', 'prenom' => 'Aminata', 'nom' => 'Traoré', 'email' => 'aminata.traore@medecine.test'],
            ['name' => 'Moussa Diarra', 'prenom' => 'Moussa', 'nom' => 'Diarra', 'email' => 'moussa.diarra@medecine.test'],
        ] as $doctor) {
            $user = User::updateOrCreate(
                ['email' => $doctor['email']],
                ['name' => $doctor['name'], 'password' => Hash::make('Medecin123!'), 'role' => 'medecin'],
            );

            Medecin::updateOrCreate(
                ['user_id' => $user->id],
                ['nom' => $doctor['nom'], 'prenom' => $doctor['prenom'], 'email' => $doctor['email']],
            );
        }
    }
}
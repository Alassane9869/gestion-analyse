<?php

namespace Database\Seeders;

use App\Models\Analyse;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Resultat;
use App\Models\TypeAnalyse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@medecine.test'],
            ['name' => 'Super Administrateur', 'password' => Hash::make('Admin123!'), 'role' => 'admin']
        );
        $patientUser = User::updateOrCreate(
            ['email' => 'patient@medecine.test'],
            ['name' => 'Mamadou Sarr', 'password' => Hash::make('password'), 'role' => 'patient']
        );
        $medecinUser = User::updateOrCreate(
            ['email' => 'medecin@medecine.test'],
            ['name' => 'Awa Diallo', 'password' => Hash::make('password'), 'role' => 'medecin']
        );

        $medecin = Medecin::firstOrCreate([
            'nom' => 'Diallo',
            'prenom' => 'Awa',
            'email' => 'awa.diallo@clinique.sn',
            'telephone' => '+221 77 111 22 33',
            'specialite' => 'Biologie médicale',
            'adresse' => 'Avenue Cheikh Anta Diop',
        ]);
        $medecin->update(['user_id' => $medecinUser->id]);

        $patient = Patient::firstOrCreate([
            'nom' => 'Sarr',
            'prenom' => 'Mamadou',
            'email' => 'mamadou.sarr@example.com',
            'telephone' => '+221 76 222 33 44',
            'date_naissance' => '1990-05-14',
            'sexe' => 'Masculin',
            'adresse' => 'Rue 10, Dakar',
        ]);
        $patient->update(['user_id' => $patientUser->id]);

        $analyses = [
            ['code' => 'GLU-FAST', 'nom' => 'Glycémie à jeun', 'description' => 'Dépistage et suivi du diabète.', 'unite' => 'mmol/L', 'prix' => 2500, 'duree_minute' => 30],
            ['code' => 'NFS', 'nom' => 'Numération formule sanguine', 'description' => 'Analyse complète des cellules sanguines.', 'unite' => 'éléments/mm³', 'prix' => 5000, 'duree_minute' => 60],
            ['code' => 'CRP', 'nom' => 'Protéine C-réactive', 'description' => 'Recherche d’une inflammation ou infection.', 'unite' => 'mg/L', 'prix' => 3500, 'duree_minute' => 45],
            ['code' => 'CREAT', 'nom' => 'Créatinine', 'description' => 'Évaluation de la fonction rénale.', 'unite' => 'mg/L', 'prix' => 3000, 'duree_minute' => 30],
            ['code' => 'UREE', 'nom' => 'Urée', 'description' => 'Bilan de la fonction rénale.', 'unite' => 'g/L', 'prix' => 3000, 'duree_minute' => 30],
            ['code' => 'BIL-T', 'nom' => 'Bilirubine totale', 'description' => 'Évaluation du foie et des voies biliaires.', 'unite' => 'mg/L', 'prix' => 3500, 'duree_minute' => 45],
            ['code' => 'TGO-TGP', 'nom' => 'Transaminases TGO/TGP', 'description' => 'Bilan biologique du foie.', 'unite' => 'UI/L', 'prix' => 5000, 'duree_minute' => 60],
            ['code' => 'CHOL', 'nom' => 'Cholestérol total', 'description' => 'Mesure du cholestérol sanguin.', 'unite' => 'g/L', 'prix' => 3000, 'duree_minute' => 30],
            ['code' => 'HIV', 'nom' => 'Sérologie VIH', 'description' => 'Dépistage sérologique du VIH avec consentement.', 'unite' => 'résultat', 'prix' => 5000, 'duree_minute' => 90],
            ['code' => 'ECBU', 'nom' => 'Examen cytobactériologique des urines', 'description' => 'Recherche d’une infection urinaire.', 'unite' => 'résultat', 'prix' => 7500, 'duree_minute' => 120],
        ];

        foreach ([
            ['nom' => 'Analyse sanguine', 'description' => 'Bilan général du sang.', 'prix' => 5000],
            ['nom' => 'Urinalyse', 'description' => 'Examen des urines.', 'prix' => 3500],
            ['nom' => 'Glycémie', 'description' => 'Mesure du taux de glucose.', 'prix' => 2500],
            ['nom' => 'Cholestérol', 'description' => 'Bilan lipidique.', 'prix' => 3000],
            ['nom' => 'Hémoglobine', 'description' => 'Mesure de l’hémoglobine.', 'prix' => 3000],
            ['nom' => 'Électrolytes', 'description' => 'Équilibre sodium et potassium.', 'prix' => 4500],
        ] as $typeAnalyse) {
            TypeAnalyse::firstOrCreate(
                ['nom' => $typeAnalyse['nom']],
                [...$typeAnalyse, 'code' => 'TA-' . strtoupper(substr(md5($typeAnalyse['nom']), 0, 8))]
            );
        }

        foreach ($analyses as $analyseData) {
            Analyse::firstOrCreate(
                ['code' => $analyseData['code']],
                $analyseData
            );
        }

        $analyse = Analyse::where('code', 'GLU-FAST')->firstOrFail();

        Resultat::firstOrCreate([
            'patient_id' => $patient->id,
            'analyse_id' => $analyse->id,
            'medecin_id' => $medecin->id,
            'valeur' => 5.4,
            'unite' => 'mmol/L',
            'date_resultat' => '2026-08-29',
            'statut' => 'normal',
            'remarques' => 'Taux dans la plage normale.',
        ]);
    }
}


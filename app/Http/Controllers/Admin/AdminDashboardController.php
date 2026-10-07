<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Resultat;
use App\Models\TypeAnalyse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $usersCount = User::count();
        $medecinsCount = User::where('role', 'medecin')->count();
        $patientsCount = User::where('role', 'patient')->count();
        $adminsCount = User::where('role', 'admin')->count();

        $totalAnalyses = Resultat::count();
        $analysesEnAttente = DB::table('commande_type_analyse')->whereIn('statut', ['en_attente', 'en_cours'])->count();
        $totalRendezVous = RendezVous::count();
        $rendezVousAujourdhui = RendezVous::whereDate('date_heure', today())->count();

        $derniersUtilisateurs = User::latest()->limit(6)->get();
        $derniersRendezVous = RendezVous::with(['patient', 'medecin'])->latest('date_heure')->limit(5)->get();
        $derniersResultats = Resultat::with(['patient', 'typeAnalyse'])->latest()->limit(5)->get();

        return view('admin.dashboard', [
            'usersCount' => $usersCount,
            'medecinsCount' => $medecinsCount,
            'patientsCount' => $patientsCount,
            'adminsCount' => $adminsCount,
            'totalAnalyses' => $totalAnalyses,
            'analysesEnAttente' => $analysesEnAttente,
            'totalRendezVous' => $totalRendezVous,
            'rendezVousAujourdhui' => $rendezVousAujourdhui,
            'derniersUtilisateurs' => $derniersUtilisateurs,
            'derniersRendezVous' => $derniersRendezVous,
            'derniersResultats' => $derniersResultats,
        ]);
    }
}

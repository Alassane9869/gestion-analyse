<?php

namespace App\Console\Commands;

use App\Models\RendezVous;
use App\Notifications\RappelRendezVousNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EnvoyerRappelsRendezVous extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rdv:rappels';

    /**
     * The console command description.
     */
    protected $description = 'Envoie les e-mails de rappel pour les rendez-vous médicaux prévus demain avec consignes de préparation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $demainDebut = Carbon::tomorrow()->startOfDay();
        $demainFin = Carbon::tomorrow()->endOfDay();

        $rdvs = RendezVous::where('statut', 'accepte')
            ->whereBetween('date_heure', [$demainDebut, $demainFin])
            ->with(['patient.user', 'medecin'])
            ->get();

        $this->info("Trouvé {$rdvs->count()} rendez-vous confirmés pour demain.");

        $count = 0;
        foreach ($rdvs as $rdv) {
            $user = $rdv->patient?->user;
            if ($user && $user->email) {
                try {
                    $user->notify(new RappelRendezVousNotification($rdv));
                    $this->line("Rappel envoyé à : {$user->email} pour RDV {$rdv->id}");
                    $count++;
                } catch (\Throwable $e) {
                    $this->error("Erreur envoi pour {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("✅ {$count} rappels envoyés avec succès.");

        return Command::SUCCESS;
    }
}

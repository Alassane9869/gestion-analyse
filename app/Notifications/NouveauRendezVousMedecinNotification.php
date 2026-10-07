<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauRendezVousMedecinNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public RendezVous $rendezVous)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $rdv = $this->rendezVous;
        $patient = $rdv->patient;
        $nomPatient = $patient ? trim($patient->prenom . ' ' . $patient->nom) : 'Patient adhérent';
        $dateHeure = $rdv->date_heure ? $rdv->date_heure->format('d/m/Y à H:i') : 'Date à définir';

        return (new MailMessage)
            ->subject('Nouvelle demande de rendez-vous : ' . $nomPatient)
            ->greeting('Bonjour Dr ' . ($rdv->medecin?->nom ?? $notifiable->name) . ',')
            ->line('Un patient a formulé une demande de rendez-vous pour un examen biologique :')
            ->line('**Patient :** ' . $nomPatient)
            ->line('**Créneau sollicité :** ' . $dateHeure)
            ->line('**Motif :** ' . ($rdv->motif ?: 'Consultation / Prélèvement biologique'))
            ->action('Gérer la demande', route('medecin.espace.dashboard'))
            ->line('Vous pouvez confirmer, reporter ou annuler ce créneau directement depuis votre tableau de bord clinique.')
            ->salutation("Cordialement,\nLa plateforme clinique BioSanté");
    }
}

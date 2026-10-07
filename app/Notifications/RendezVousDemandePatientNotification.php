<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RendezVousDemandePatientNotification extends Notification implements ShouldQueue
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
        $medecin = $rdv->medecin;
        $nomMedecin = $medecin ? 'Dr ' . trim($medecin->prenom . ' ' . $medecin->nom) : 'votre médecin biologiste';
        $dateHeure = $rdv->date_heure ? $rdv->date_heure->format('d/m/Y à H:i') : 'Date à définir';

        return (new MailMessage)
            ->subject('Demande de rendez-vous enregistrée - BioSanté')
            ->greeting('Bonjour ' . ($patient?->prenom ? $patient->prenom : $notifiable->name) . ',')
            ->line('Votre demande de rendez-vous médical a bien été enregistrée sur notre plateforme.')
            ->line('**Médecin sollicité :** ' . $nomMedecin)
            ->line('**Créneau demandé :** ' . $dateHeure)
            ->line('**Statut :** En attente de validation par le cabinet médical')
            ->line('Vous recevrez une notification par e-mail dès que le médecin aura validé votre créneau.')
            ->action('Voir mes rendez-vous', route('patient.rendez-vous'))
            ->salutation("Cordialement,\nLe secrétariat médical BioSanté Analyses");
    }
}

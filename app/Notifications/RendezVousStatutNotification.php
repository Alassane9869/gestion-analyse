<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RendezVousStatutNotification extends Notification implements ShouldQueue
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
        $nomMedecin = $medecin ? 'Dr ' . trim($medecin->prenom . ' ' . $medecin->nom) : 'votre médecin';

        $statutLibelle = match ($rdv->statut) {
            'accepte' => 'Confirmé / Accepté',
            'refuse' => 'Refusé / Annulé',
            default => 'En attente de confirmation',
        };

        $dateHeure = $rdv->date_heure ? $rdv->date_heure->format('d/m/Y à H:i') : 'Date à définir';

        $mail = (new MailMessage)
            ->subject('Mise à jour de votre rendez-vous : ' . $statutLibelle)
            ->greeting('Bonjour ' . ($patient?->prenom ? $patient->prenom : $notifiable->name) . ',')
            ->line('Le statut de votre rendez-vous médical a été mis à jour.')
            ->line('**Médecin :** ' . $nomMedecin)
            ->line('**Date et heure :** ' . $dateHeure)
            ->line('**Nouveau statut :** ' . $statutLibelle);

        if ($rdv->statut === 'accepte') {
            $mail->line('Veuillez vous présenter 10 minutes avant l’heure prévue muni de votre pièce d’identité.');
        } elseif ($rdv->statut === 'refuse') {
            $mail->line('Votre demande de rendez-vous n’a pas pu être retenue pour ce créneau. Nous vous invitons à choisir une autre date sur votre portail.');
        }

        return $mail
            ->action('Accéder à mes rendez-vous', route('patient.rendez-vous'))
            ->salutation('Cordialement, Le cabinet médical');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'rendez_vous_id' => $this->rendezVous->id,
            'statut' => $this->rendezVous->statut,
            'date_heure' => $this->rendezVous->date_heure?->toIso8601String(),
        ];
    }
}

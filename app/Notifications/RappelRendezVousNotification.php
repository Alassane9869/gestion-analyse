<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RappelRendezVousNotification extends Notification implements ShouldQueue
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
        $dateHeure = $rdv->date_heure ? $rdv->date_heure->format('d/m/Y à H:i') : 'votre heure prévue';

        return (new MailMessage)
            ->subject('Rappel : Votre rendez-vous médical demain à ' . ($rdv->date_heure ? $rdv->date_heure->format('H:i') : '') . ' - BioSanté')
            ->greeting('Bonjour ' . ($patient?->prenom ? $patient->prenom : $notifiable->name) . ',')
            ->line('Nous vous rappelons votre rendez-vous de prélèvement médical prévu **demain**.')
            ->line('**Date et heure :** ' . $dateHeure)
            ->line('**Praticien référent :** ' . $nomMedecin)
            ->line('**Lieu :** Laboratoire BioSanté Analyses')
            ->line('---')
            ->line('### ⚠️ Consignes médicales importantes :')
            ->line('• **À jeun :** Si votre examen comprend un bilan glycémique ou lipidique (cholestérol), restez impérativement à jeun depuis la veille (au moins 8 à 12h avant le prélèvement). Boire de l\'eau plate reste autorisé.')
            ->line('• **Documents à présenter :** Veuillez vous munir de votre ordonnance médicale originale et d\'une pièce d\'identité en cours de validité.')
            ->line('• **Heure d\'arrivée :** Merci de vous présenter 10 minutes avant l\'horaire fixé.')
            ->action('Consulter les détails du rendez-vous', route('patient.rendez-vous'))
            ->salutation("Cordialement,\nL'équipe médicale BioSanté Analyses");
    }
}

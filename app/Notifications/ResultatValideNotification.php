<?php

namespace App\Notifications;

use App\Models\Resultat;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultatValideNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Resultat $resultat)
    {
    }

    public function via(object $notifiable): array
    {
        $channels = ['mail'];

        if (config('services.twilio.sid') && $this->resultat->patient?->whatsapp_opt_in) {
            $channels[] = WhatsAppChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $analyseNom = $this->resultat->typeAnalyse?->nom ?? $this->resultat->analyse?->nom ?? 'Analyse médicale';
        $patient = $this->resultat->patient;
        $bulletinUrl = route('resultats.bulletin', $this->resultat);

        $mail = (new MailMessage)
            ->subject('Résultat disponible : ' . $analyseNom)
            ->greeting('Bonjour ' . ($patient?->prenom ? $patient->prenom : $notifiable->name) . ',')
            ->line('Le résultat de votre analyse médicale **' . $analyseNom . '** est désormais validé et consultable dans votre espace santé.')
            ->line('**Date d’enregistrement :** ' . ($this->resultat->date_resultat?->format('d/m/Y') ?? now()->format('d/m/Y')))
            ->line('**Statut du résultat :** ' . ucfirst($this->resultat->statut));

        if ($this->resultat->valeur !== null) {
            $mail->line('**Valeur mesurée :** ' . $this->resultat->valeur . ' ' . ($this->resultat->unite ?? ''));
        }

        if ($this->resultat->remarques) {
            $mail->line('**Remarques du médecin :** ' . $this->resultat->remarques);
        }

        return $mail
            ->action('Consulter mon bulletin médical', $bulletinUrl)
            ->line('Vous pouvez télécharger et imprimer votre bulletin d’analyse officiel directement depuis votre espace personnel.')
            ->salutation('Cordialement, Le laboratoire d’analyses médicales');
    }

    public function toWhatsApp(object $notifiable): array
    {
        return ['resultat' => $this->resultat];
    }

    public function toArray(object $notifiable): array
    {
        $phone = preg_replace('/\D+/', '', (string) $this->resultat->patient?->whatsapp_phone);
        $analyseNom = $this->resultat->typeAnalyse?->nom ?? $this->resultat->analyse?->nom ?? 'médical';
        $message = 'Votre résultat pour l’analyse ' . $analyseNom . ' est disponible sur votre portail.';

        return [
            'resultat_id' => $this->resultat->id,
            'analyse' => $analyseNom,
            'fallback_url' => $phone ? 'https://wa.me/' . $phone . '?text=' . urlencode($message) : null,
        ];
    }
}

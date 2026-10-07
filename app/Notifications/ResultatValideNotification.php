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

        // Pièce jointe automatique du bulletin officiel PDF certifié
        try {
            $pdfService = app(\App\Services\PdfBulletinService::class);
            $pdf = $pdfService->genererPdf($this->resultat);
            $pdfOutput = $pdf->output();
            $nomFichier = 'Bulletin_BioSante_' . sprintf('BIO-%s-%06d', date('Y'), $this->resultat->id) . '.pdf';
            $mail->attachData($pdfOutput, $nomFichier, [
                'mime' => 'application/pdf',
            ]);
            $mail->line('📎 **Votre bulletin d’analyse officiel certifié est joint à cet e-mail au format PDF.**');
        } catch (\Throwable $e) {
            report($e);
        }

        return $mail
            ->action('Consulter mon bulletin en ligne', $bulletinUrl)
            ->line('Vous pouvez également consulter et télécharger vos bilans à tout moment depuis votre espace patient sécurisé.')
            ->salutation("Cordialement,\nL'équipe médicale BioSanté Analyses");
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

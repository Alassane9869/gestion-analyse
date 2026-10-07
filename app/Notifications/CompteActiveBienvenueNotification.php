<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompteActiveBienvenueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenue sur votre Espace Santé BioSanté Analyses')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre adresse e-mail a été vérifiée et votre compte patient est désormais **pleinement activé**.')
            ->line('Vous disposez à présent d’un accès complet et confidentiel aux services du laboratoire :')
            ->line('• **Résultats d’examens en temps réel :** Consultez et téléchargez vos bulletins officiels certifiés au format PDF.')
            ->line('• **Prise de rendez-vous :** Choisissez un médecin biologiste et réservez votre créneau de prélèvement.')
            ->line('• **Historique biologique :** Retrouvez l’ensemble de vos bilans de santé antérieurs sécurisés.')
            ->action('Accéder à mon espace santé', route('dashboard'))
            ->line('Pour toute question médicale ou d’ordre technique, notre secrétariat reste à votre entière disposition.')
            ->salutation("Cordialement,\nL'équipe médicale BioSanté Analyses");
    }
}

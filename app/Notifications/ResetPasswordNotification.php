<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Réinitialisation sécurisée de votre mot de passe - BioSanté')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte **BioSanté Analyses**.')
            ->line('Pour définir un nouveau mot de passe, cliquez sur le bouton ci-dessous :')
            ->action('Réinitialiser mon mot de passe', $resetUrl)
            ->line('Ce lien de réinitialisation expirera dans **60 minutes**.')
            ->line('**Rappel de sécurité :** Si vous n’avez pas formulé cette demande, aucune action supplémentaire n’est requise. Votre compte et vos données médicales restent protégés.')
            ->salutation("Cordialement,\nLe service sécurité BioSanté Analyses");
    }
}

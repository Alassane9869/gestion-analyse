<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpVerificationNotification extends Notification
{
    use Queueable;

    public function __construct(public string $otpCode)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre code de vérification BioSanté : ' . $this->otpCode)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Bienvenue sur la plateforme clinique **BioSanté Analyses**.')
            ->line('Pour finaliser la création de votre compte et accéder à votre espace patient en toute sécurité, veuillez saisir le code de vérification suivant :')
            ->line('## **' . $this->otpCode . '**')
            ->line('Ce code confidentiel est valable pendant **15 minutes**.')
            ->line('Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer cet e-mail en toute sécurité.')
            ->salutation('Cordialement, Le laboratoire BioSanté Analyses');
    }
}

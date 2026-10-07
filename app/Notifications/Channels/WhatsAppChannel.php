<?php

namespace App\Notifications\Channels;

use App\Services\WhatsAppResultatService;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toWhatsApp($notifiable);
        app(WhatsAppResultatService::class)->send($message['resultat']);
    }
}

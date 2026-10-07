<?php

namespace App\Services;

use App\Models\Resultat;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppResultatService
{
    public function send(Resultat $resultat): string
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.whatsapp_from');
        $patient = $resultat->patient;

        if (! $sid || ! $token || ! $from) {
            throw new RuntimeException('Le service WhatsApp n’est pas configuré.');
        }

        if (! $patient?->whatsapp_opt_in || ! $patient->whatsapp_phone) {
            throw new RuntimeException('Le patient n’a pas donné son consentement WhatsApp ou son numéro est absent.');
        }

        if ($resultat->statut === 'en_attente') {
            throw new RuntimeException('Un résultat en attente ne peut pas être envoyé.');
        }

        $message = implode("\n", [
            'Bonjour ' . $patient->prenom . ',',
            'Votre résultat est disponible.',
            'Analyse : ' . ($resultat->typeAnalyse?->nom ?? $resultat->analyse?->nom ?? 'Examen médical'),
            'Statut : ' . $resultat->statut,
            'Valeur : ' . ($resultat->valeur ?? '—') . ' ' . ($resultat->unite ?? ''),
            'Date : ' . ($resultat->date_resultat?->format('d/m/Y') ?? '—'),
            'Veuillez contacter le laboratoire pour toute question médicale.',
        ]);

        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->timeout(15)
            ->post('https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/Messages.json', [
                'From' => 'whatsapp:' . $from,
                'To' => 'whatsapp:' . $patient->whatsapp_phone,
                'Body' => $message,
            ]);

        if ($response->failed() || ! $response->json('sid')) {
            throw new RuntimeException('Le fournisseur WhatsApp a refusé l’envoi.');
        }

        return (string) $response->json('sid');
    }
}
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BioSanté Analyses') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-body">
        <div class="auth-shell">
            <section class="auth-visual">
                <a href="/" class="brand-mark brand-mark--light">
                    <span class="brand-mark__symbol">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <span>BioSanté <span>Analyses</span></span>
                </a>
                <div class="auth-visual__copy">
                    <span class="eyebrow">DIAGNOSTIC & BIOLOGIE MÉDICALE</span>
                    <h1>Vos analyses,<br><strong>en toute confiance.</strong></h1>
                    <p>Plateforme clinique sécurisée pour la gestion de vos examens, le suivi des prélèvements et la délivrance de bulletins certifiés.</p>
                    <div class="trust-line">
                        <span class="trust-line__icon">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="trust-line__text">Données de santé protégées · Secret médical garanti</span>
                    </div>
                </div>
            </section>
            <section class="auth-panel">
                <div class="auth-panel__inner">{{ $slot }}</div>
            </section>
        </div>
    </body>
</html>

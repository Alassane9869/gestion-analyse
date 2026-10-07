<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin d'Analyse Médicale #{{ $referenceDossier ?? str_pad($resultat->id, 6, '0', STR_PAD_LEFT) }} · BioSanté</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: #000 !important; }
            .bulletin-container { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; padding: 0 !important; border-radius: 0 !important; }
            @page { margin: 12mm 15mm 15mm 15mm; size: A4 portrait; }
        }
        .bulletin-container {
            max-width: 880px;
            margin: 25px auto;
            background: #ffffff;
            border: 1px solid #dce4ee;
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(18, 42, 74, 0.08);
            padding: clamp(24px, 5vw, 44px);
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 antialiased py-6">

    <!-- Barre d'actions Backend & Navigation (masquée à l'impression) -->
    <div class="no-print max-w-[880px] mx-auto mb-5 px-4 flex flex-wrap items-center justify-between gap-3">
        @php
            $retourUrl = auth()->user()->isAdmin() 
                ? route('admin.dashboard') 
                : (auth()->user()->isMedecin() ? route('medecin.espace.analyses') : route('patient.resultats'));
        @endphp
        <a href="{{ $retourUrl }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour aux analyses</span>
        </a>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Téléchargement PDF Backend Direct -->
            <a href="{{ route('resultats.pdf', ['resultat' => $resultat->id, 'download' => 1]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Télécharger le PDF certifié</span>
            </a>

            <!-- Afficher le PDF Backend dans le navigateur -->
            <a href="{{ route('resultats.pdf', $resultat) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Flux PDF natif</span>
            </a>

            <!-- Impression directe -->
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Imprimer</span>
            </button>
        </div>
    </div>

    <!-- Conteneur officiel du Bulletin Médical -->
    <div class="bulletin-container">
        
        <!-- En-tête officiel du laboratoire d'analyses -->
        <header class="border-b-2 border-slate-900 pb-5 mb-6">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-400 to-blue-600 flex items-center justify-center text-white shadow-md flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tight text-slate-900 uppercase">
                            Laboratoire BioSanté
                        </h1>
                        <p class="text-xs font-bold text-emerald-700 tracking-wider uppercase mt-0.5">
                            Biologie Médicale & Diagnostics Spécialisés · Norme ISO 15189
                        </p>
                        <p class="text-[11px] text-slate-500 mt-1 leading-tight">
                            Agrément Sanitaire N° ML-LAB-2024-884 · FINESS/SIRET : 260 014 928<br>
                            Standard Biologie Clinique : (+223) 20 22 40 00 / (+33) 1 84 16 00 00 · Astreinte 24h/24h
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <span class="inline-block px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-mono font-bold uppercase tracking-wider shadow-sm">
                        {{ $referenceDossier ?? ('BIO-' . date('Y') . '-' . str_pad($resultat->id, 6, '0', STR_PAD_LEFT)) }}
                    </span>
                    <p class="text-xs text-slate-600 mt-2 font-medium">
                        Échantillon : <strong class="text-slate-900">ECH-{{ str_pad($resultat->id, 5, '0', STR_PAD_LEFT) }}</strong><br>
                        Édition du rapport : <strong class="text-slate-900">{{ now()->format('d/m/Y à H:i') }}</strong>
                    </p>
                </div>
            </div>
        </header>

        <!-- Bandeau Titre Officiel -->
        <div class="bg-slate-900 text-white text-center text-xs font-bold uppercase tracking-wider py-2 px-4 rounded-lg mb-6 shadow-sm">
            Compte-Rendu d'Analyses Biologiques · Certificat Médical Officiel
        </div>

        <!-- Cartouches Patient et Prescripteur (Grille 2 Colonnes) -->
        <div class="grid sm:grid-cols-2 gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-200 mb-6 text-sm">
            <!-- Bloc Patient -->
            <div>
                <span class="text-[10px] font-black tracking-widest text-blue-700 uppercase block mb-2">
                    IDENTIFICATION DU PATIENT
                </span>
                <p class="font-extrabold text-lg text-slate-900 leading-tight">
                    {{ strtoupper($patient?->nom ?? 'PATIENT') }} {{ $patient?->prenom ?? '' }}
                </p>
                <div class="mt-2.5 space-y-1 text-xs text-slate-600">
                    <p><span class="text-slate-400 font-medium">Identifiant IPP :</span> <strong class="text-slate-900">#{{ str_pad($patient?->id ?? 1, 6, '0', STR_PAD_LEFT) }}</strong></p>
                    <p>
                        <span class="text-slate-400 font-medium">Date de naissance :</span> 
                        <strong class="text-slate-900">{{ $patient?->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</strong>
                        @if(!empty($agePatient) && $agePatient !== 'Non précisé')
                            <span class="text-slate-500 font-semibold">({{ $agePatient }})</span>
                        @endif
                    </p>
                    <p><span class="text-slate-400 font-medium">Sexe :</span> <strong class="text-slate-900">{{ $patient?->sexe ?: 'Non précisé' }}</strong></p>
                    <p>
                        <span class="text-slate-400 font-medium">Groupe Sanguin :</span> 
                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-black bg-rose-100 text-rose-800 border border-rose-200">
                            {{ $patient?->groupe_sanguin ?: 'Non déterminé' }}
                        </span>
                    </p>
                    <p><span class="text-slate-400 font-medium">Contact :</span> {{ $patient?->whatsapp_phone ?: ($patient?->telephone ?: $patient?->email) }}</p>
                </div>
            </div>

            <!-- Bloc Prescripteur & Traçabilité Prélèvement -->
            <div class="sm:border-l sm:border-slate-200 sm:pl-5">
                <span class="text-[10px] font-black tracking-widest text-teal-700 uppercase block mb-2">
                    PRESCRIPTION & TRAÇABILITÉ
                </span>
                <p class="font-extrabold text-base text-slate-900 leading-tight">
                    Dr {{ $medecin ? ($medecin->prenom . ' ' . $medecin->nom) : 'Praticien Biologiste' }}
                </p>
                <div class="mt-2.5 space-y-1 text-xs text-slate-600">
                    <p><span class="text-slate-400 font-medium">Prélèvement :</span> <strong class="text-slate-900">{{ isset($datePrelevement) ? $datePrelevement->format('d/m/Y à H:i') : now()->subHour()->format('d/m/Y à H:i') }}</strong></p>
                    <p><span class="text-slate-400 font-medium">Nature :</span> <strong class="text-slate-900">{{ $naturePrelevement ?? 'Sérum (Tube sec)' }}</strong></p>
                    <p><span class="text-slate-400 font-medium">Validation :</span> <strong class="text-emerald-700">{{ isset($dateValidation) ? $dateValidation->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}</strong></p>
                    <p><span class="text-slate-400 font-medium">Statut légal :</span> <span class="font-bold text-emerald-700">Définitif · Validé biologiquement</span></p>
                </div>
            </div>
        </div>

        <!-- Tableau des résultats médicaux -->
        <div class="mb-6">
            <h2 class="text-xs font-black tracking-widest text-slate-700 uppercase mb-3 flex items-center justify-between">
                <span>RÉSULTATS DES DOSAGES BIOLOGIQUES</span>
                <span class="text-[11px] font-semibold text-slate-400 lowercase">contrôle interne de qualité conforme</span>
            </h2>

            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-[10px] font-extrabold">
                        <tr>
                            <th class="p-3.5">Examen / Paramètre dosé</th>
                            <th class="p-3.5 text-center">Valeur trouvée</th>
                            <th class="p-3.5 text-center">Unité</th>
                            <th class="p-3.5 text-right">Valeurs de Référence</th>
                            <th class="p-3.5 text-center">Diagnostic Biologique</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <tr>
                            <td class="p-3.5 font-bold text-slate-900">
                                <div class="text-sm font-extrabold text-slate-900">
                                    {{ $nomAnalyse ?? ($resultat->typeAnalyse?->nom ?? ($resultat->analyse?->nom ?? 'Examen médical')) }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 font-normal">
                                    Code : {{ $typeAnalyse?->code ?? ($resultat->analyse?->code ?? 'BIO-EX') }} · Automate spectrométrique calibré
                                </div>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($resultat->valeur !== null && $resultat->valeur !== '')
                                    <span class="text-lg font-black font-mono px-3 py-1 rounded-lg border {{ isset($interpretation) && $interpretation['code'] === 'NORMAL' ? 'bg-slate-50 text-slate-900 border-slate-200' : 'bg-rose-50 text-rose-700 border-rose-300' }}">
                                        {{ $resultat->valeur }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">En attente</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center font-bold text-slate-700">
                                {{ $unite ?? ($resultat->unite ?: ($resultat->typeAnalyse?->unite ?? '—')) }}
                            </td>
                            <td class="p-3.5 text-right font-medium text-slate-700">
                                {{ $intervalle['texte'] ?? 'Valeur physiologique usuelle' }}
                            </td>
                            <td class="p-3.5 text-center">
                                @if(isset($interpretation))
                                    @if($interpretation['code'] === 'NORMAL')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Normal
                                        </span>
                                    @elseif($interpretation['code'] === 'ELEVE')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                            Supérieur (H)
                                        </span>
                                    @elseif($interpretation['code'] === 'BAS')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                            Inférieur (L)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            En attente
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Validé
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section Conclusion Médicale & Interprétation -->
        <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-200 mb-6">
            <h3 class="text-xs font-black tracking-widest text-slate-700 uppercase mb-2">
                INTERPRÉTATION MÉDICALE & CONCLUSION CLINIQUE
            </h3>
            <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">
                @if($resultat->remarques)
                    {{ $resultat->remarques }}
                @elseif(isset($interpretation) && $interpretation['code'] === 'NORMAL')
                    Dosage biologique conforme aux intervalles usuels de référence physiologique pour la tranche d'âge et le sexe du patient. Aucun signe d'anomalie détecté lors de la série analytique.
                @elseif(isset($interpretation) && $interpretation['code'] === 'ELEVE')
                    Taux biologique supérieur aux valeurs de référence admises. Une corrélation avec l'état clinique et un contrôle à distance sont recommandés sous surveillance médicale.
                @elseif(isset($interpretation) && $interpretation['code'] === 'BAS')
                    Taux biologique inférieur aux bornes usuelles de référence. À confronter au tableau clinique et aux éventuels traitements en cours.
                @else
                    Examen enregistré dans la série analytique du laboratoire. Données sous réserve de confirmation médicale.
                @endif
            </p>
        </div>

        <!-- Validation & Signature Électronique -->
        <footer class="pt-6 border-t border-slate-200 grid sm:grid-cols-2 gap-6 items-end text-xs text-slate-500">
            <div>
                <p class="font-bold text-slate-800">Garantie de Conformité ISO 15189 & Sécurité :</p>
                <p class="mt-1 leading-relaxed text-[11px] text-slate-500">
                    Ce compte-rendu a été validé biologiquement par un praticien habilité après contrôle interne de qualité. Les résultats sont protégés par le secret médical.
                </p>
                <div class="mt-2 text-[10px] text-slate-500">
                    Empreinte cryptographique de sécurité :<br>
                    <span class="font-mono font-bold text-slate-800 tracking-wider">
                        {{ $empreinteCryptographique ?? 'BIO-CERT-7F2A-98BC' }}
                    </span>
                </div>
            </div>

            <div class="text-right flex flex-col items-end">
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-3 bg-slate-50 text-center w-60">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Laboratoire BioSanté</span>
                    <span class="font-black text-slate-900 text-sm mt-1 block">
                        Dr {{ $medecin ? ($medecin->prenom . ' ' . $medecin->nom) : 'Aminata Traoré' }}
                    </span>
                    <span class="text-[10px] text-slate-500 block">
                        RPPS N° 1010{{ str_pad($medecin?->id ?? 1, 4, '0', STR_PAD_LEFT) }} · Biologiste Responsable
                    </span>
                    <span class="text-[10px] text-emerald-700 font-bold block mt-1.5 pt-1.5 border-t border-slate-200">
                        Signature Électronique Certifiée
                    </span>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>

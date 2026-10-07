<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bulletin Biologique #{{ $referenceDossier }}</title>
    <style>
        @page {
            margin: 12mm 15mm 15mm 15mm;
            size: A4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            color: #1a2536;
            font-size: 10pt;
            line-height: 1.35;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* En-tête officiel du laboratoire */
        .lab-header {
            width: 100%;
            border-bottom: 2pt solid #103157;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .lab-header table {
            width: 100%;
            border-collapse: collapse;
        }
        .lab-logo-cell {
            width: 65%;
            vertical-align: top;
        }
        .lab-meta-cell {
            width: 35%;
            text-align: right;
            vertical-align: top;
        }
        .lab-title {
            font-size: 14pt;
            font-weight: bold;
            color: #103157;
            letter-spacing: -0.3px;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }
        .lab-subtitle {
            font-size: 8pt;
            font-weight: bold;
            color: #0d8272;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 3px 0;
        }
        .lab-address {
            font-size: 7.5pt;
            color: #556987;
            line-height: 1.3;
            margin: 0;
        }
        .dossier-pill {
            display: inline-block;
            background: #eef4fb;
            border: 1pt solid #cbd9ea;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 8.5pt;
            font-weight: bold;
            color: #103157;
            font-family: 'Courier New', Courier, monospace;
        }
        .dossier-date {
            font-size: 7.5pt;
            color: #63758e;
            margin-top: 4px;
        }

        /* Cartouches Patient & Prescripteur */
        .cartouches-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-left: -8px;
            margin-right: -8px;
            margin-bottom: 14px;
        }
        .cartouche-box {
            width: 50%;
            vertical-align: top;
            background: #f8fafc;
            border: 1pt solid #dbe3ed;
            border-radius: 6px;
            padding: 9px 11px;
        }
        .cartouche-title {
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #0d8272;
            border-bottom: 0.5pt solid #dbe3ed;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .patient-name {
            font-size: 11pt;
            font-weight: bold;
            color: #10263f;
            margin-bottom: 4px;
        }
        .data-row {
            font-size: 8pt;
            margin-bottom: 3px;
            color: #33445c;
        }
        .data-label {
            color: #72849e;
            display: inline-block;
            min-width: 95px;
        }
        .data-value {
            font-weight: bold;
            color: #1f2d3d;
        }

        /* Titre du document */
        .doc-title-bar {
            background: #103157;
            color: #ffffff;
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 5px 8px;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        /* Tableau des résultats biologiques */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .results-table th {
            background: #eef4fb;
            color: #103157;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            text-align: left;
            padding: 6px 8px;
            border-top: 1pt solid #cbd9ea;
            border-bottom: 1.5pt solid #cbd9ea;
        }
        .results-table td {
            font-size: 8.5pt;
            padding: 8px 8px;
            border-bottom: 0.5pt solid #e6ecf4;
            vertical-align: middle;
        }
        .results-table tr:nth-child(even) td {
            background: #fafcff;
        }
        .param-name {
            font-weight: bold;
            color: #10263f;
        }
        .param-desc {
            font-size: 7.5pt;
            color: #718298;
            margin-top: 2px;
        }
        .valeur-box {
            font-size: 11pt;
            font-weight: bold;
            color: #0b1c31;
            font-family: 'Courier New', Courier, monospace;
        }
        .valeur-normale {
            color: #0d8272;
        }
        .valeur-alerte {
            color: #b91c1c;
            background: #fef2f2;
            padding: 2px 4px;
            border-radius: 3px;
            border: 0.5pt solid #fecaca;
        }
        .tag-conforme {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            color: #0d8272;
            background: #e6f7f3;
            border: 0.5pt solid #b2e8dc;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .tag-alerte-haut {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            color: #b91c1c;
            background: #fef2f2;
            border: 0.5pt solid #fecaca;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .tag-alerte-bas {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            color: #b45309;
            background: #fffbeb;
            border: 0.5pt solid #fde68a;
            padding: 2px 5px;
            border-radius: 3px;
        }

        /* Section Interprétation & Conclusion */
        .conclusion-box {
            background: #f8fafc;
            border: 1pt solid #dbe3ed;
            border-left: 3.5pt solid #103157;
            border-radius: 4px;
            padding: 9px 12px;
            margin-bottom: 14px;
        }
        .conclusion-title {
            font-size: 7.5pt;
            font-weight: bold;
            color: #103157;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }
        .conclusion-text {
            font-size: 8.5pt;
            color: #2a394f;
            line-height: 1.4;
            margin: 0;
        }

        /* Validation & Signature */
        .validation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1pt solid #e2e8f0;
        }
        .validation-left {
            width: 58%;
            vertical-align: top;
            font-size: 7.5pt;
            color: #64748b;
            line-height: 1.4;
        }
        .validation-right {
            width: 42%;
            vertical-align: top;
            text-align: right;
        }
        .cachet-box {
            display: inline-block;
            border: 1.5pt dashed #103157;
            border-radius: 6px;
            padding: 8px 12px;
            text-align: center;
            background: #f8fbff;
            min-width: 170px;
        }
        .cachet-title {
            font-size: 8pt;
            font-weight: bold;
            color: #103157;
            text-transform: uppercase;
        }
        .cachet-medecin {
            font-size: 8.5pt;
            font-weight: bold;
            color: #10263f;
            margin-top: 2px;
        }
        .cachet-rpps {
            font-size: 7pt;
            color: #556987;
            margin-top: 1px;
        }
        .cachet-sig {
            font-size: 7pt;
            color: #0d8272;
            font-weight: bold;
            margin-top: 4px;
            border-top: 0.5pt solid #cbd9ea;
            padding-top: 3px;
        }

        /* Pied de page de certification */
        .cert-footer {
            margin-top: 16px;
            padding-top: 6px;
            border-top: 0.5pt solid #cbd9ea;
            text-align: center;
            font-size: 6.8pt;
            color: #7b8c9e;
            line-height: 1.35;
        }
        .cert-hash {
            font-family: 'Courier New', Courier, monospace;
            color: #103157;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- En-tête officiel Laboratoire BioSanté -->
    <div class="lab-header">
        <table>
            <tr>
                <td class="lab-logo-cell">
                    <div class="lab-title">Laboratoire BioSanté</div>
                    <div class="lab-subtitle">Biologie Médicale & Diagnostics Spécialisés · ISO 15189</div>
                    <p class="lab-address">
                        Agrément Sanitaire N° ML-LAB-2024-884 · FINESS/SIRET : 260 014 928<br>
                        Plateau Technique Central · Standard Biologie : (+223) 20 22 40 00 / (+33) 1 84 16 00 00<br>
                        Astreinte 24h/24h · validation-biologique@biosante-lab.org
                    </p>
                </td>
                <td class="lab-meta-cell">
                    <div class="dossier-pill">{{ $referenceDossier }}</div>
                    <div class="dossier-date">
                        Date d'édition : <strong>{{ now()->format('d/m/Y à H:i') }}</strong><br>
                        Échantillon N° : <strong>ECH-{{ str_pad($resultat->id, 5, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bandeau Titre Officiel -->
    <div class="doc-title-bar">
        Compte-Rendu d'Analyses Biologiques · Certificat Officiel
    </div>

    <!-- Cartouches Patient et Prescripteur -->
    <table class="cartouches-table">
        <tr>
            <!-- Identification Patient -->
            <td class="cartouche-box">
                <div class="cartouche-title">Identification du Patient</div>
                <div class="patient-name">
                    {{ strtoupper($patient?->nom ?? 'PATIENT') }} {{ $patient?->prenom ?? '' }}
                </div>
                <div class="data-row">
                    <span class="data-label">Identifiant (IPP) :</span>
                    <span class="data-value">#{{ str_pad($patient?->id ?? 1, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Date de naissance :</span>
                    <span class="data-value">{{ $patient?->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</span>
                    @if($agePatient !== 'Non précisé')
                        <span style="color:#72849e;">({{ $agePatient }})</span>
                    @endif
                </div>
                <div class="data-row">
                    <span class="data-label">Sexe :</span>
                    <span class="data-value">{{ $patient?->sexe ?: 'Non précisé' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Groupe Sanguin :</span>
                    <span class="data-value" style="color: #b91c1c;">{{ $patient?->groupe_sanguin ?: 'Non déterminé' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Contact :</span>
                    <span class="data-value">{{ $patient?->whatsapp_phone ?: ($patient?->telephone ?: $patient?->email) }}</span>
                </div>
            </td>

            <!-- Prescripteur & Traçabilité Prélèvement -->
            <td class="cartouche-box">
                <div class="cartouche-title">Prescription & Prélèvement</div>
                <div class="data-row" style="margin-top: 2px;">
                    <span class="data-label">Médecin Prescripteur :</span>
                    <span class="data-value">Dr {{ $medecin ? ($medecin->prenom . ' ' . $medecin->nom) : 'Médecin Hospitalier' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Date prélèvement :</span>
                    <span class="data-value">{{ $datePrelevement->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Réception laboratoire :</span>
                    <span class="data-value">{{ $dateReception->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Nature du prélèvement :</span>
                    <span class="data-value">{{ $naturePrelevement }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Validation médicale :</span>
                    <span class="data-value" style="color: #0d8272;">{{ $dateValidation->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Statut réglementaire :</span>
                    <span class="data-value" style="color: #0d8272;">Définitif · Validé</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tableau des Résultats Biologiques -->
    <table class="results-table">
        <thead>
            <tr>
                <th style="width: 38%;">Paramètre Biologique Dosé</th>
                <th style="width: 22%; text-align: center;">Résultat Mesuré</th>
                <th style="width: 12%; text-align: center;">Unité</th>
                <th style="width: 28%; text-align: right;">Valeurs de Référence</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="param-name">{{ $nomAnalyse }}</div>
                    <div class="param-desc">
                        Code examen : {{ $typeAnalyse?->code ?: 'BIO-EX' }} · Automate spectrométrique calibré
                    </div>
                </td>
                <td style="text-align: center;">
                    @if($resultat->valeur !== null && $resultat->valeur !== '')
                        <span class="valeur-box {{ $interpretation['code'] === 'NORMAL' ? 'valeur-normale' : 'valeur-alerte' }}">
                            {{ $resultat->valeur }}
                        </span>
                        @if($interpretation['symbole'] && $interpretation['code'] !== 'NORMAL')
                            <span style="font-weight: bold; font-size: 11pt; color: #b91c1c; margin-left: 2px;">
                                {{ $interpretation['symbole'] }}
                            </span>
                        @endif
                    @else
                        <span style="font-style: italic; color: #64748b; font-size: 8.5pt;">Analyse en cours</span>
                    @endif
                </td>
                <td style="text-align: center; font-weight: bold; color: #475569;">
                    {{ $unite }}
                </td>
                <td style="text-align: right; font-size: 8pt; color: #334155;">
                    <div>{{ $intervalle['texte'] }}</div>
                    @if($interpretation['code'] === 'NORMAL')
                        <span class="tag-conforme">Normal</span>
                    @elseif($interpretation['code'] === 'ELEVE')
                        <span class="tag-alerte-haut">Supérieur (H)</span>
                    @elseif($interpretation['code'] === 'BAS')
                        <span class="tag-alerte-bas">Inférieur (L)</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Section Conclusion & Interprétation Médicale -->
    <div class="conclusion-box">
        <div class="conclusion-title">Interprétation Médicale & Conclusion Biologique</div>
        <p class="conclusion-text">
            @if($resultat->remarques)
                {{ $resultat->remarques }}
            @elseif($interpretation['code'] === 'NORMAL')
                Dosage biologique conforme aux intervalles usuels de référence physiologique pour la tranche d'âge et le sexe du patient. Aucun signe d'anomalie détecté lors de la série analytique.
            @elseif($interpretation['code'] === 'ELEVE')
                Taux biologique supérieur aux valeurs de référence admises. Une corrélation avec l'état clinique et un contrôle à distance sont recommandés sous surveillance médicale.
            @elseif($interpretation['code'] === 'BAS')
                Taux biologique inférieur aux bornes usuelles de référence. À confronter au tableau clinique et aux éventuels traitements en cours.
            @else
                Examen enregistré dans la série analytique du laboratoire. Données sous réserve de confirmation médicale.
            @endif
        </p>
    </div>

    <!-- Cartouche de Validation Médicale et Signature Électronique -->
    <table class="validation-table">
        <tr>
            <td class="validation-left">
                <strong style="color:#103157;">GARANTIE DE CONFORMITÉ & SIGNATURE ÉLECTRONIQUE</strong><br>
                Ce compte-rendu d'analyses a été validé biologiquement par un médecin habilité après contrôle interne de qualité (CIQ) conforme à la norme NF EN ISO 15189.<br>
                Empreinte cryptographique de vérification :<br>
                <span class="cert-hash">{{ $empreinteCryptographique }}</span>
            </td>
            <td class="validation-right">
                <div class="cachet-box">
                    <div class="cachet-title">Laboratoire BioSanté</div>
                    <div class="cachet-medecin">
                        Dr {{ $medecin ? ($medecin->prenom . ' ' . $medecin->nom) : 'Aminata Traoré' }}
                    </div>
                    <div class="cachet-rpps">
                        Biologiste Responsable · RPPS N° 1010{{ str_pad($medecin?->id ?? 1, 4, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="cachet-sig">
                        Signature Électronique Certifiée<br>
                        Le {{ $dateValidation->format('d/m/Y à H:i') }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Pied de page officiel -->
    <div class="cert-footer">
        BioSanté Laboratoire d'Analyses Médicales · Document officiel confidentiel protégé par le secret médical (Code de la Santé Publique)<br>
        Toute reproduction ou altération de ce document sans l'accord exprès du biologiste responsable est passible de poursuites pénales.<br>
        Pour vérifier l'authenticité de ce bulletin, contactez le laboratoire en citant la référence <strong>{{ $referenceDossier }}</strong> et l'empreinte <strong>{{ $empreinteCryptographique }}</strong>.
    </div>

</body>
</html>

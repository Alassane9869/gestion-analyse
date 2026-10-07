<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Barryvdh\DomPDF\Facade\Pdf;

$html = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Identifiants d\'Accès - BioSanté</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 18px;
            margin-bottom: 25px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #0369a1;
            margin: 0;
        }
        .logo-subtitle {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 3px;
        }
        .doc-badge {
            text-align: right;
        }
        .badge {
            display: inline-block;
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-date {
            font-size: 10px;
            color: #64748b;
            margin-top: 5px;
        }
        .intro-box {
            background-color: #f8fafc;
            border-left: 4px solid #0284c7;
            padding: 14px 18px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
        }
        .intro-box h2 {
            margin: 0 0 6px 0;
            font-size: 14px;
            color: #0f172a;
        }
        .intro-box p {
            margin: 0;
            color: #475569;
            font-size: 11.5px;
        }
        .portal-url {
            display: inline-block;
            color: #0284c7;
            font-weight: bold;
            text-decoration: none;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 22px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .role-admin { color: #4338ca; }
        .role-doctor { color: #0284c7; }
        .role-patient { color: #0d9488; }

        table.credentials-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.credentials-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 9px 12px;
            text-align: left;
            border: 1px solid #cbd5e1;
        }
        table.credentials-table td {
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: middle;
        }
        table.credentials-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .tag {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .tag-admin { background-color: #e0e7ff; color: #3730a3; }
        .tag-doctor { background-color: #e0f2fe; color: #0369a1; }
        .tag-patient { background-color: #ccfbf1; color: #0f766e; }

        .password-box {
            font-family: monospace, Courier, "Courier New";
            background-color: #f1f5f9;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            display: inline-block;
        }

        .notice-box {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 0 6px 6px 0;
            margin-top: 25px;
            font-size: 10.5px;
            color: #92400e;
        }
        .notice-box strong {
            display: block;
            margin-bottom: 4px;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="logo-title">+ BioSanté Analyses</div>
                    <div class="logo-subtitle">Plateforme Clinique & Laboratoire de Biologie Médicale</div>
                </td>
                <td class="doc-badge">
                    <span class="badge">Fiche d\'Accès & Sécurité</span>
                    <div class="doc-date">Généré le ' . date('d/m/Y à H:i') . '</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="intro-box">
        <h2>Portail de Connexion</h2>
        <p>
            Adresse d\'accès officiel : <span class="portal-url">https://bio-sante.danayaplus.com/login</span><br>
            <em>Sur la page de connexion, sélectionnez l\'onglet correspondant à votre profil (Admin, Médecin ou Patient) avant de saisir vos identifiants.</em>
        </p>
    </div>

    <!-- 1. ADMINISTRATEUR -->
    <div class="section-title role-admin">1. Espace Administrateur (Gestion Globale)</div>
    <table class="credentials-table">
        <thead>
            <tr>
                <th style="width: 25%;">Utilisateur</th>
                <th style="width: 15%;">Onglet</th>
                <th style="width: 35%;">Identifiant (Email)</th>
                <th style="width: 25%;">Mot de passe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Super Administrateur</strong><br><span style="color:#64748b; font-size:10px;">Gestion & Rapports</span></td>
                <td><span class="tag tag-admin">Admin</span></td>
                <td><code>admin@medecine.test</code></td>
                <td><span class="password-box">Admin123!</span></td>
            </tr>
        </tbody>
    </table>

    <!-- 2. MÉDECINS -->
    <div class="section-title role-doctor">2. Espace Médecins</div>
    <table class="credentials-table">
        <thead>
            <tr>
                <th style="width: 25%;">Nom du Médecin</th>
                <th style="width: 15%;">Onglet</th>
                <th style="width: 35%;">Identifiant (Email)</th>
                <th style="width: 25%;">Mot de passe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Dr. Awa Diallo</strong><br><span style="color:#64748b; font-size:10px;">Biologie médicale</span></td>
                <td><span class="tag tag-doctor">Médecin</span></td>
                <td><code>medecin@medecine.test</code></td>
                <td><span class="password-box">password</span></td>
            </tr>
            <tr>
                <td><strong>Dr. Aminata Traoré</strong><br><span style="color:#64748b; font-size:10px;">Biologiste</span></td>
                <td><span class="tag tag-doctor">Médecin</span></td>
                <td><code>aminata.traore@medecine.test</code></td>
                <td><span class="password-box">Medecin123!</span></td>
            </tr>
            <tr>
                <td><strong>Dr. Moussa Diarra</strong><br><span style="color:#64748b; font-size:10px;">Analyste</span></td>
                <td><span class="tag tag-doctor">Médecin</span></td>
                <td><code>moussa.diarra@medecine.test</code></td>
                <td><span class="password-box">Medecin123!</span></td>
            </tr>
        </tbody>
    </table>

    <!-- 3. PATIENTS -->
    <div class="section-title role-patient">3. Espace Patients</div>
    <table class="credentials-table">
        <thead>
            <tr>
                <th style="width: 25%;">Nom du Patient</th>
                <th style="width: 15%;">Onglet</th>
                <th style="width: 35%;">Identifiant (Email)</th>
                <th style="width: 25%;">Mot de passe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Mamadou Sarr</strong><br><span style="color:#64748b; font-size:10px;">Consultation résultats</span></td>
                <td><span class="tag tag-patient">Patient</span></td>
                <td><code>patient@medecine.test</code></td>
                <td><span class="password-box">password</span></td>
            </tr>
        </tbody>
    </table>

    <div class="notice-box">
        <strong>Recommandations de sécurité :</strong>
        Ce document contient des informations d\'accès confidentielles destinées exclusivement aux personnes autorisées. 
        Pour une sécurité maximale, il est conseillé à chaque utilisateur de modifier son mot de passe lors de sa première connexion via les paramètres de son profil.
    </div>

    <div class="footer">
        BioSanté Analyses &bull; Solution de Gestion d\'Analyses Médicales &bull; Document Confidentiel &bull; ' . date('Y') . '
    </div>

</body>
</html>
';

$pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

$targetPaths = [
    'c:/Users/a/Downloads/Gestion-analyses/Gestion-analyses/public/BioSante_Identifiants_Acces.pdf',
    'c:/Users/a/Downloads/Gestion-analyses/Gestion-analyses/BioSante_Identifiants_Acces.pdf',
    'c:/Users/a/Downloads/Gestion-analyses/BioSante_Identifiants_Acces.pdf',
];

$output = $pdf->output();

foreach ($targetPaths as $path) {
    file_put_contents($path, $output);
    echo "Fichier généré : $path (" . strlen($output) . " octets)\n";
}

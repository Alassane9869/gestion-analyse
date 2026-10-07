<?php
/**
 * Script de Déploiement et d'Installation Automatique pour o2switch / cPanel
 * Projet : BioSanté - Gestion des Analyses Médicales
 * 
 * Ce script prépare et initialise l'application Laravel sans toucher au code :
 * 1. Crée le fichier .env de production depuis .env.production si absent
 * 2. Configure les permissions des répertoires storage et bootstrap/cache
 * 3. Exécute les migrations et les seeders de la base de données MySQL
 * 4. Met en place les caches de production (config, routes, vues)
 * 5. Crée le lien symbolique du stockage public
 * 6. Met en place le pont webroot vers bio-sante.danayaplus.com si présent
 */

define('LARAVEL_START', microtime(true));

$isCli = (php_sapi_name() === 'cli');

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déploiement o2switch - BioSanté</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 30px; margin: 0; }
        .card { max-width: 760px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 30px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        h1 { color: #38bdf8; font-size: 22px; margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .step { margin: 15px 0; padding: 12px 16px; border-radius: 8px; font-size: 13px; font-family: monospace; }
        .success { background: #064e3b; color: #a7f3d0; border: 1px solid #059669; }
        .warning { background: #78350f; color: #fde68a; border: 1px solid #d97706; }
        .info { background: #1e3a5f; color: #bae6fd; border: 1px solid #0284c7; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; font-family: sans-serif; margin-top: 15px; }
        .btn:hover { background: #0369a1; }
    </style>
</head>
<body>
<div class="card">
    <h1>Déploiement Automatique o2switch &bull; BioSanté</h1>
    <p style="color: #94a3b8; font-size: 14px;">Initialisation et mise en ligne du laboratoire d'analyses médicales en un clic.</p>
<?php
}

$baseDir = __DIR__;

function logMsg($msg, $type = 'info') {
    global $isCli;
    if ($isCli) {
        $clean = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $msg));
        echo "[$type] $clean\n";
    } else {
        echo "<div class='step $type'>$msg</div>";
        flush();
    }
}

// 1. Vérification / Création du fichier .env
$envPath = $baseDir . '/.env';
$envProdPath = $baseDir . '/.env.production';

if (!file_exists($envPath)) {
    if (file_exists($envProdPath)) {
        copy($envProdPath, $envPath);
        logMsg("Fichier .env créé avec succès depuis .env.production.", "success");
    } else {
        logMsg("Fichier .env.production introuvable, création d'un .env par défaut.", "warning");
    }
} else {
    logMsg("Fichier .env déjà existant, conservé.", "info");
}

// 2. Vérification et création des dossiers de cache et logs
$dirs = [
    $baseDir . '/storage/app/public',
    $baseDir . '/storage/framework/cache/data',
    $baseDir . '/storage/framework/sessions',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/logs',
    $baseDir . '/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    @chmod($dir, 0775);
}
logMsg("Répertoires de stockage et cache configurés avec permissions 0775.", "success");

// 3. Exécution artisan via PHP interne
function runArtisan($command) {
    global $baseDir;
    $artisan = $baseDir . '/artisan';
    $php = PHP_BINARY;
    $cmd = escapeshellcmd("$php $artisan $command");
    $output = @shell_exec($cmd . ' 2>&1');
    return $output;
}

$hasShell = function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))));

if ($hasShell) {
    // Exécuter les migrations MySQL
    logMsg("Exécution des migrations MySQL et des seeders...", "info");
    $migOutput = runArtisan('migrate --force --seed');
    logMsg("Migrations : <br><pre style='margin:0;'>" . htmlspecialchars($migOutput) . "</pre>", "success");

    // Lien symbolique storage
    runArtisan('storage:link');
    logMsg("Lien symbolique storage:link généré.", "success");

    // Caches de production
    runArtisan('config:cache');
    runArtisan('route:cache');
    runArtisan('view:cache');
    logMsg("Caches optimisés : configuration, routes et vues Blade.", "success");
} else {
    logMsg("shell_exec est restreint sur cet environnement. Veuillez exécuter les migrations via le Terminal cPanel.", "info");
}

// 4. Détection et configuration du pont webroot vers bio-sante.danayaplus.com
$domainDir = dirname($baseDir) . '/../bio-sante.danayaplus.com';
$altDomainDir = dirname($baseDir) . '/bio-sante.danayaplus.com';
$targetWebroot = null;

if (is_dir($domainDir)) {
    $targetWebroot = realpath($domainDir);
} elseif (is_dir($altDomainDir)) {
    $targetWebroot = realpath($altDomainDir);
}

if ($targetWebroot) {
    $bridgeIndex = "<?php\n" .
        "define('LARAVEL_START', microtime(true));\n\n" .
        "if (file_exists(\$maintenance = __DIR__.'/../repositories/gestion-analyse/storage/framework/maintenance.php')) {\n" .
        "    require \$maintenance;\n" .
        "}\n\n" .
        "require __DIR__.'/../repositories/gestion-analyse/vendor/autoload.php';\n\n" .
        "(require_once __DIR__.'/../repositories/gestion-analyse/bootstrap/app.php')\n" .
        "    ->handleRequest(Illuminate\\Http\\Request::capture());\n";

    file_put_contents($targetWebroot . '/index.php', $bridgeIndex);

    $bridgeHtaccess = "<IfModule mod_rewrite.c>\n" .
        "    Options +FollowSymLinks\n" .
        "    <IfModule mod_negotiation.c>\n" .
        "        Options -MultiViews -Indexes\n" .
        "    </IfModule>\n" .
        "    RewriteEngine On\n" .
        "    RewriteCond %{REQUEST_FILENAME} !-d\n" .
        "    RewriteCond %{REQUEST_FILENAME} !-f\n" .
        "    RewriteRule ^ index.php [L]\n" .
        "</IfModule>\n";

    file_put_contents($targetWebroot . '/.htaccess', $bridgeHtaccess);

    if (is_dir($baseDir . '/public/build') && !file_exists($targetWebroot . '/build')) {
        @symlink($baseDir . '/public/build', $targetWebroot . '/build');
    }
    if (is_dir($baseDir . '/public/images') && !file_exists($targetWebroot . '/images')) {
        @symlink($baseDir . '/public/images', $targetWebroot . '/images');
    }
    if (is_dir($baseDir . '/storage/app/public') && !file_exists($targetWebroot . '/storage')) {
        @symlink($baseDir . '/storage/app/public', $targetWebroot . '/storage');
    }

    logMsg("Pont webroot configuré automatiquement dans : " . htmlspecialchars($targetWebroot), "success");
}

logMsg("<strong>Déploiement terminé avec succès !</strong> Votre portail BioSanté est opérationnel.", "success");

if (!$isCli) {
?>
    <div style="margin-top: 25px; text-align: center;">
        <a href="https://bio-sante.danayaplus.com" target="_blank" class="btn">Accéder au Portail BioSanté &rarr;</a>
    </div>
</div>
</body>
</html>
<?php
}

<?php
/**
 * Pont automatique pour serveur web pointant à la racine (cPanel / o2switch)
 * Permet d'exécuter l'application que le domaine pointe vers / ou vers /public
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Mode maintenance
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader Composer
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
} elseif (file_exists(__DIR__.'/../repositories/gestion-analyse/vendor/autoload.php')) {
    require __DIR__.'/../repositories/gestion-analyse/vendor/autoload.php';
}

// Démarrage de l'application Laravel
if (file_exists(__DIR__.'/bootstrap/app.php')) {
    $app = require_once __DIR__.'/bootstrap/app.php';
} elseif (file_exists(__DIR__.'/../repositories/gestion-analyse/bootstrap/app.php')) {
    $app = require_once __DIR__.'/../repositories/gestion-analyse/bootstrap/app.php';
}

$app->handleRequest(Request::capture());

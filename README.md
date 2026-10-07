<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Gestion des analyses médicales

Application Laravel 13 en français, avec Blade, Tailwind CSS, MySQL et authentification Breeze.

### Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Configurer ensuite MySQL dans `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), puis exécuter :

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Pour le développement avec Vite :

```bash
composer run dev
```

### Comptes de démonstration

- Patient : `patient@medecine.test` / `password`
- Médecin : `medecin@medecine.test` / `password`

Le choix du profil est demandé sur la page de connexion. L’inscription libre crée toujours un compte patient.

### Fonctionnalités

- Patient : profil, groupe sanguin, téléphone WhatsApp au format `+223XXXXXXXX`, sélection multiple des types d’analyses, total en FCFA et rendez-vous.
- Médecin : rendez-vous, CRUD des types d’analyses, saisie et consultation paginée des résultats.
- Résultats : statuts `En attente`, `En cours`, `Complétée`, avec patient, groupe sanguin, type, date et valeur.
- Autorisation : middleware `role:patient` et `role:medecin`, avec routes séparées et validation serveur.
- WhatsApp : notification Laravel queueable via le canal `WhatsAppChannel`. Renseigner `TWILIO_SID`, `TWILIO_TOKEN` et `TWILIO_WHATSAPP_FROM` pour Twilio; le lien `wa.me` est disponible comme secours dans les données de notification.

### Tests

```bash
php artisan test
npm run build
```

Pour traiter les notifications WhatsApp en développement :

```bash
php artisan queue:work
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

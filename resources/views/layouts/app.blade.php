<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion des analyses') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <div class="portal-shell">
        <!-- Topbar Mobile -->
        <div class="mobile-topbar">
            <a href="{{ route('dashboard') }}" class="brand-mark">
                <span class="brand-mark__symbol">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </span>
                <span>BioSanté</span>
            </a>
            <button class="icon-button menu-toggle" type="button" aria-controls="user-sidebar" aria-expanded="false" data-menu-toggle>
                <span class="sr-only">Ouvrir le menu</span>
                <span class="hamburger"><i></i><i></i><i></i></span>
            </button>
        </div>
        <div class="navigation-backdrop" data-menu-close></div>

        <!-- Sidebar Utilisateur (Desktop Sticky & Mobile Drawer) -->
        <aside id="user-sidebar" class="portal-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-mark">
                    <span class="brand-mark__symbol">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <span>BioSanté</span>
                </a>
                <button class="icon-button sidebar-close" type="button" aria-label="Fermer le menu" data-menu-close>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="sidebar-caption">MON ESPACE</div>

            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('dashboard*') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </span>
                    <span>Tableau de bord</span>
                </a>

                @if(auth()->user()->isPatient())
                    <a class="sidebar-link {{ request()->routeIs('patient.profil*') ? 'is-active' : '' }}" href="{{ route('patient.profil') }}">
                        <span class="nav-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <span>Mon profil</span>
                    </a>
                    <a class="sidebar-link {{ request()->routeIs('patient.analyses*') ? 'is-active' : '' }}" href="{{ route('patient.analyses') }}">
                        <span class="nav-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </span>
                        <span>Mes analyses</span>
                    </a>
                @elseif(auth()->user()->isAdmin())
                    <a class="sidebar-link {{ request()->routeIs('admin.dashboard*') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span>Console Admin</span>
                    </a>
                @else
                    <a class="sidebar-link {{ request()->routeIs('medecin.resultats*') ? 'is-active' : '' }}" href="{{ route('medecin.resultats') }}">
                        <span class="nav-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span>Résultats</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="profile-chip">
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span>
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->isAdmin() ? 'Administrateur' : (auth()->user()->isPatient() ? 'Patient' : 'Médecin') }}</small>
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="sidebar-logout" type="submit">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Contenu Principal -->
        <div class="portal-main">
            <header class="portal-header">
                <div>
                    <span class="header-kicker">ESPACE PERSONNEL</span>
                    <h1 class="text-slate-900 font-extrabold text-lg sm:text-xl">Mon profil</h1>
                </div>
                <div class="header-user">
                    <span class="header-status"><i></i> En ligne</span>
                    <span class="avatar avatar--small">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
            </header>

            <main class="portal-content">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

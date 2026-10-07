<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    @php($profilMedecin = auth()->user()->medecin)
    @php($nomMedecin = trim(($profilMedecin?->prenom ?? '').' '.($profilMedecin?->nom ?? '')) ?: auth()->user()->name)
    <div class="portal-shell">
        <div class="mobile-topbar">
            <a href="{{ route('medecin.espace.dashboard') }}" class="brand-mark">
                <span class="brand-mark__symbol">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </span>
                <span>BioSanté <span>Analyses</span></span>
            </a>
            <button class="icon-button menu-toggle" type="button" aria-controls="medecin-sidebar" aria-expanded="false" data-menu-toggle>
                <span class="sr-only">Ouvrir le menu</span>
                <span class="hamburger"><i></i><i></i><i></i></span>
            </button>
        </div>
        <div class="navigation-backdrop" data-menu-close></div>

        <aside id="medecin-sidebar" class="portal-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('medecin.espace.dashboard') }}" class="brand-mark">
                    <span class="brand-mark__symbol">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <span>BioSanté <span>Analyses</span></span>
                </a>
                <button class="icon-button sidebar-close" type="button" aria-label="Fermer le menu" data-menu-close>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="sidebar-caption">ESPACE PRATICIEN</div>

            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('medecin.espace.dashboard') ? 'is-active' : '' }}" href="{{ route('medecin.espace.dashboard') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </span>
                    <span>Tableau de bord</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('medecin.espace.patients*') ? 'is-active' : '' }}" href="{{ route('medecin.espace.patients') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                    <span>Dossiers Patients</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('medecin.espace.rendez-vous*') ? 'is-active' : '' }}" href="{{ route('medecin.espace.rendez-vous') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <span>Rendez-vous</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('medecin.espace.analyses*') ? 'is-active' : '' }}" href="{{ route('medecin.espace.analyses') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </span>
                    <span>Analyses à traiter</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('medecin.types*') ? 'is-active' : '' }}" href="{{ route('medecin.types.index') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </span>
                    <span>Catalogue analyses</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('medecin.resultats*') ? 'is-active' : '' }}" href="{{ route('medecin.resultats') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <span>Résultats & Bulletins</span>
                </a>

                @if(auth()->user()->isAdmin())
                <a class="sidebar-link text-indigo-300 hover:text-white bg-indigo-950/40 border border-indigo-500/30 mb-1" href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </span>
                    <span>Console Admin</span>
                </a>
                @endif

                <a class="sidebar-link {{ request()->routeIs('profile*') ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <span>Sécurité & Compte</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="profile-chip">
                    <span class="avatar">{{ strtoupper(substr($nomMedecin, 0, 1)) }}</span>
                    <span>
                        <strong>Dr {{ $nomMedecin }}</strong>
                        <small>Praticien biologiste</small>
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="sidebar-logout" type="submit">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="portal-main">
            <header class="portal-header">
                <div>
                    <span class="header-kicker">ESPACE CLINIQUE & BIOLOGIE</span>
                    <h1>@yield('page-heading', 'Gestion des analyses')</h1>
                </div>

                <div class="header-user">
                    <form method="GET" action="{{ route('medecin.espace.patients') }}" class="hidden md:flex items-center relative">
                        <span class="absolute left-3 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" 
                               name="search" 
                               placeholder="Rechercher un dossier patient..." 
                               class="w-56 lg:w-72 pl-9 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition outline-none">
                    </form>

                    <a href="{{ route('medecin.espace.patients.create') }}" 
                       class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Nouveau patient</span>
                    </a>

                    <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <span class="text-xs font-bold text-slate-800 block leading-tight">Dr {{ $nomMedecin }}</span>
                            <span class="text-[10px] text-emerald-600 font-semibold flex items-center justify-end gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Service actif
                            </span>
                        </div>
                        <span class="avatar avatar--small">{{ strtoupper(substr($nomMedecin, 0, 1)) }}</span>
                    </div>
                </div>
            </header>

            <main class="portal-content">
                @if(session('success'))
                    <div class="toast toast--success" data-toast>
                        <span class="toast__icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ session('success') }}</span>
                        <button type="button" data-toast-close aria-label="Fermer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="toast toast--error" data-toast>
                        <span class="toast__icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </span>
                        <span>{{ $errors->first() }}</span>
                        <button type="button" data-toast-close aria-label="Fermer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
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
    <div class="portal-shell">
        <!-- Topbar Mobile -->
        <div class="mobile-topbar">
            <a href="{{ route('admin.dashboard') }}" class="brand-mark">
                <span class="brand-mark__symbol">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </span>
                <span>BioSanté <span class="text-teal-400">Admin</span></span>
            </a>
            <button class="icon-button menu-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" data-menu-toggle>
                <span class="sr-only">Ouvrir le menu</span>
                <span class="hamburger"><i></i><i></i><i></i></span>
            </button>
        </div>
        <div class="navigation-backdrop" data-menu-close></div>

        <!-- Sidebar Administration (Sticky) -->
        <aside id="admin-sidebar" class="portal-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-mark">
                    <span class="brand-mark__symbol">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <span>BioSanté <span class="text-teal-400">Admin</span></span>
                </a>
                <button class="icon-button sidebar-close" type="button" aria-label="Fermer le menu" data-menu-close>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="sidebar-caption">ADMINISTRATION GLOBALE</div>

            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </span>
                    <span>Tableau de bord</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('admin.users*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </span>
                    <span>Utilisateurs & Accès</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('medecin.*') ? 'is-active' : '' }}" href="{{ route('medecin.espace.dashboard') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </span>
                    <span>Vue Laboratoire</span>
                </a>

                <a class="sidebar-link {{ request()->routeIs('profile*') ? 'is-active' : '' }}" href="{{ route('profile.edit') }}">
                    <span class="nav-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <span>Mon Compte</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="profile-chip">
                    <span class="avatar bg-indigo-600 text-white font-black">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span>
                        <strong>{{ auth()->user()->name }}</strong>
                        <small class="text-teal-300">Super Administrateur</small>
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

        <!-- Contenu Principal -->
        <div class="portal-main">
            <!-- Header Fixe (Sticky) -->
            <header class="portal-header">
                <div>
                    <span class="header-kicker text-indigo-600 font-extrabold">CONSOLE D'ADMINISTRATION SYSTÈME</span>
                    <h1 class="text-slate-900 font-extrabold text-lg sm:text-xl">@yield('page-heading', 'Administration Générale')</h1>
                </div>

                <div class="header-user">
                    <a href="{{ route('admin.users.create') }}" 
                       class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Créer un utilisateur</span>
                    </a>

                    <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <span class="text-xs font-bold text-slate-800 block leading-tight">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">Accès Racine</span>
                        </div>
                        <span class="avatar avatar--small bg-indigo-600 text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
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

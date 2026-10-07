@extends('layouts.admin')
@section('title', 'Gestion des Utilisateurs')
@section('page-heading', 'Gestion des Utilisateurs & Droits')

@section('content')
<div class="space-y-6">
    <!-- Barre d'actions & En-tête -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Répertoire des Utilisateurs</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Gérez l'ensemble des comptes de la plateforme : médecins biologistes, patients et administrateurs.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-sm transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Créer un utilisateur</span>
            </a>
        </div>
    </div>

    <!-- Filtres par Rôle (Tabs) & Recherche -->
    <div class="rounded-2xl bg-white p-4 sm:p-5 shadow-sm ring-1 ring-slate-200/80 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Onglets de filtre -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.users.index', array_filter(['search' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ empty($roleFilter) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <span>Tous</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ empty($roleFilter) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $totalCount }}</span>
                </a>

                <a href="{{ route('admin.users.index', array_filter(['role' => 'medecin', 'search' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $roleFilter === 'medecin' ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    <span>Médecins</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $roleFilter === 'medecin' ? 'bg-white/20 text-white' : 'bg-blue-200/60 text-blue-800' }}">{{ $medecinsCount }}</span>
                </a>

                <a href="{{ route('admin.users.index', array_filter(['role' => 'patient', 'search' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $roleFilter === 'patient' ? 'bg-teal-600 text-white shadow-sm' : 'bg-teal-50 text-teal-700 hover:bg-teal-100' }}">
                    <span>Patients</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $roleFilter === 'patient' ? 'bg-white/20 text-white' : 'bg-teal-200/60 text-teal-800' }}">{{ $patientsCount }}</span>
                </a>

                <a href="{{ route('admin.users.index', array_filter(['role' => 'admin', 'search' => $search])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $roleFilter === 'admin' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                    <span>Administrateurs</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $roleFilter === 'admin' ? 'bg-white/20 text-white' : 'bg-indigo-200/60 text-indigo-800' }}">{{ $adminsCount }}</span>
                </a>
            </div>

            <!-- Formulaire de recherche -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2 w-full lg:w-80">
                @if(!empty($roleFilter))
                    <input type="hidden" name="role" value="{{ $roleFilter }}">
                @endif
                <div class="relative flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Rechercher nom, e-mail..." 
                           class="w-full pl-9 pr-4 py-2 rounded-xl text-xs sm:text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
                    Filtrer
                </button>
                @if($search !== '' || !empty($roleFilter))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition" title="Réinitialiser">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                        <th class="py-3.5 px-4 sm:px-6">Utilisateur</th>
                        <th class="py-3.5 px-4">Rôle</th>
                        <th class="py-3.5 px-4">Profil & Coordonnées</th>
                        <th class="py-3.5 px-4">Inscription</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Nom & Email -->
                            <td class="py-3.5 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-xs text-white flex-shrink-0 shadow-sm
                                        {{ $user->role === 'admin' ? 'bg-indigo-600' : ($user->role === 'medecin' ? 'bg-blue-600' : 'bg-teal-600') }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate flex items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-indigo-100 text-indigo-700">Vous</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-500 truncate block">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Rôle -->
                            <td class="py-3.5 px-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-100 text-indigo-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                                        <span>Administrateur</span>
                                    </span>
                                @elseif($user->role === 'medecin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        <span>Médecin</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-100 text-teal-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                        <span>Patient</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Profil & Coordonnées -->
                            <td class="py-3.5 px-4">
                                @if($user->role === 'medecin')
                                    <div class="space-y-0.5">
                                        <span class="font-semibold text-slate-800 block text-xs">
                                            {{ $user->medecin?->specialite ?? 'Biologie médicale' }}
                                        </span>
                                        <span class="text-slate-500 text-[11px] block">
                                            {{ $user->medecin?->telephone ?? 'Téléphone non renseigné' }}
                                        </span>
                                    </div>
                                @elseif($user->role === 'patient')
                                    <div class="space-y-0.5">
                                        <span class="font-semibold text-slate-800 block text-xs">
                                            Groupe {{ $user->patient?->groupe_sanguin ?? 'Inconnu' }}
                                        </span>
                                        <span class="text-slate-500 text-[11px] block">
                                            {{ $user->patient?->telephone ?? 'Téléphone non renseigné' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">Droits Racine / Superviseur</span>
                                @endif
                            </td>

                            <!-- Date de création -->
                            <td class="py-3.5 px-4 text-slate-500 text-xs">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold text-xs border border-slate-200 transition"
                                       title="Modifier l'utilisateur">
                                        Modifier
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Confirmez-vous la suppression définitive du compte {{ addslashes($user->name) }} ? Cette action est irréversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 font-semibold text-xs border border-slate-200 transition"
                                                    title="Supprimer">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">Aucun utilisateur trouvé</p>
                                    <p class="text-xs text-slate-400">Aucun compte ne correspond à vos critères de recherche ou de filtre.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

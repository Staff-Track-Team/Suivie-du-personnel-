@extends('layouts.admin')

@section('title', 'Détails Administrateur')

@section('content')
<div class="h-full w-full">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.admins.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">{{ $admin->name }}</h1>
            </div>
            <div class="flex items-center gap-2 ml-10">
                <span class="text-sm text-slate-500">{{ $admin->matricule }}</span>
                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">Admin Système</span>
                @if($admin->id === Auth::id())
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Vous</span>
                @endif
                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $admin->status === 'Actif' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700' }}">
                    {{ $admin->status }}
                </span>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Modifier
            </a>
            @if($admin->id !== Auth::id())
            <div x-data="{ deleteAdminOpen: false }">
                <button type="button" @click="deleteAdminOpen = true" class="px-4 py-2 bg-white text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Supprimer
                </button>

                <x-confirm-modal 
                    id="deleteAdmin"
                    title="Supprimer l'administrateur"
                    message="Voulez-vous vraiment supprimer définitivement le profil de {{ $admin->name }} ?"
                    confirmText="Oui, supprimer"
                />

                <form id="form-deleteAdmin" x-ref="formdeleteAdmin" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Informations principales (2/3) -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Carte Profil -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <div class="p-2 bg-white border border-slate-100 rounded-lg text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Profil Administrateur</h2>
                </div>
                
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">
                        <div class="shrink-0 text-center">
                            <div class="w-32 h-32 rounded-full border-4 border-slate-100 shadow-sm overflow-hidden mb-3 relative">
                                @if($admin->profil)
                                    <img src="{{ asset($admin->profil) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-4xl font-bold text-white">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs font-mono text-slate-400">{{ $admin->matricule }}</span>
                        </div>

                        <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-400 mb-1">Nom complet</label>
                                <div class="font-medium text-slate-800">{{ $admin->name }}</div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-400 mb-1">Email</label>
                                <div class="font-medium text-slate-800">{{ $admin->email }}</div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-400 mb-1">Téléphone</label>
                                <div class="font-medium text-slate-800">{{ $admin->code_phone }} {{ $admin->phone ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-400 mb-1">Genre</label>
                                <div class="font-medium text-slate-800">{{ $admin->gender ?? 'Non spécifié' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-400 mb-1">Date de naissance</label>
                                <div class="font-medium text-slate-800">{{ $admin->birthday ? \Carbon\Carbon::parse($admin->birthday)->format('d/m/Y') : '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs uppercase font-bold text-slate-400 mb-1">Inscrit le</label>
                                <div class="font-medium text-slate-800">{{ $admin->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Block (Affichage) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-orange-50 border-b border-orange-100 flex items-center gap-3">
                    <div class="p-2 bg-orange-100 text-orange-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Niveau d'Accès</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-orange-50/50 border border-orange-100">
                        <div class="p-3 bg-white rounded-full shadow-sm text-orange-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Accès Total</h4>
                            <p class="text-sm text-slate-600">Cet utilisateur dispose de tous les droits d'administration sur la plateforme.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions (1/3) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
                <div class="p-4 border-b border-slate-100 font-bold text-slate-800">Actions Rapides</div>
                <div class="p-4 flex flex-col gap-2">
                    <a href="{{ route('admin.admins.edit', $admin->id) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span class="font-medium">Modifier le profil</span>
                    </a>
                    
                    @if($admin->id !== Auth::id())
                        @if($admin->status === 'Actif')
                        <form action="{{ route('admin.admins.toggle-status', $admin->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 text-slate-600 hover:text-orange-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                <span class="font-medium">Désactiver le compte</span>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.admins.toggle-status', $admin->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-green-50 text-slate-600 hover:text-green-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="font-medium">Activer le compte</span>
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Carte Statut -->
            <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 hover:shadow-md transition-all duration-300">
                <h3 class="font-bold text-indigo-900 mb-4">État du profil</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-bold text-indigo-700 mb-1">
                            <span>Complétude</span>
                            <span>
                                @php
                                    $completion = 0;
                                    if ($admin->name) $completion += 20;
                                    if ($admin->email) $completion += 20;
                                    if ($admin->phone) $completion += 20;
                                    if ($admin->gender) $completion += 15;
                                    if ($admin->profil) $completion += 25;
                                @endphp
                                {{ $completion }}%
                            </span>
                        </div>
                        <div class="h-2 bg-white rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $completion }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

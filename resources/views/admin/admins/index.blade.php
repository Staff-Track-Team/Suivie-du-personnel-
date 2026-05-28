@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Administrateurs Système')
@section('page-title', 'Gestion des Administrateurs Système')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backToDashboard="true" />
    </div>
<!-- Injection de styles spécifiques -->
<style>
    .card-stat {
        transition: transform 0.2s, box-shadow 0.2s;
        margin: 3px;
        border-radius: 12px;
    }
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .filter-section {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
        border-radius: 12px;
        border: 1px solid #e3e6f0;
    }
    .bulk-actions {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #ffeaa7;
    }
    .table-actions {
        white-space: nowrap;
    }
    .admin-row {
        transition: background-color 0.2s;
        border-radius: 8px;
    }
    .admin-row:hover {
        background-color: #f8f9fa;
    }
    .admin-avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
    }
    .admin-avatar-placeholder {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 0.8rem;
        font-weight: 700;
        border: 2px solid #e9ecef;
    }
    .search-results-info {
        background: #e7f3ff;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #007bff;
    }
    .select-all-checkbox {
        margin-right: 10px;
    }
    .admin-checkbox {
        margin-right: 8px;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .action-buttons .btn {
        border-radius: 6px;
        margin: 2px;
    }
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        background: #f8f9fa;
        border-radius: 12px;
        margin: 2rem 0;
    }
</style>

<!-- En-tête et statistiques -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Gestion des Administrateurs Système</h2>
            <p class="text-slate-500">Administration système - Gestion des super administrateurs</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between card-stat">
            <div>
                <h4 class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</h4>
                <p class="text-slate-500 text-sm">Total</p>
            </div>
            <div class="text-blue-500 bg-white p-2 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <div class="bg-green-50 p-4 rounded-xl border border-green-100 flex items-center justify-between card-stat">
            <div>
                <h4 class="text-2xl font-bold text-green-600">{{ $stats['actifs'] }}</h4>
                <p class="text-slate-500 text-sm">Actifs</p>
            </div>
            <div class="text-green-500 bg-white p-2 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-center justify-between card-stat">
            <div>
                <h4 class="text-2xl font-bold text-gray-600">{{ $stats['inactifs'] }}</h4>
                <p class="text-slate-500 text-sm">Inactifs</p>
            </div>
            <div class="text-gray-500 bg-white p-2 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <h3 class="font-bold text-lg text-slate-800">Liste des Administrateurs</h3>
        </div>
        <div class="flex gap-2">
             <!-- Dropdown Export (Simplifié pour Tailwind/Alpine) -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="px-4 py-2 border border-blue-200 text-blue-600 rounded-xl hover:bg-blue-50 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Exporter
                </button>
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-10" style="display: none;">
                    <form action="{{ route('admin.admins.download-pdf') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            PDF
                        </button>
                    </form>
                    <form action="{{ route('admin.admins.download-excel') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                             Excel
                        </button>
                    </form>
                </div>
            </div>

            <a href="{{ route('admin.admins.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center gap-2 shadow-lg shadow-green-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel Admin
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Filtres -->
        <div class="filter-section p-4 mb-6">
            <form action="{{ route('admin.admins.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-blue-600 mb-1 uppercase">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, matricule..." class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        <div class="absolute left-3 top-2.5 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-blue-600 mb-1 uppercase">Statut</label>
                    <select name="statut" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="Activer" {{ request('statut') == 'Activer' ? 'selected' : '' }}>Actif</option>
                        <option value="Desactiver" {{ request('statut') == 'Desactiver' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-blue-600 mb-1 uppercase">Genre</label>
                    <select name="gender" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">Filtrer</button>
                </div>
            </form>
        </div>

        @if($admins->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-500 text-sm uppercas tracking-wider bg-slate-50">
                        <th class="p-4 font-semibold">Admin</th>
                        <th class="p-4 font-semibold">Contact</th>
                        <th class="p-4 font-semibold hidden md:table-cell">Infos</th>
                        <th class="p-4 font-semibold text-center">Statut</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($admins as $admin)
                    <tr class="hover:bg-slate-50 transition-colors admin-row">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($admin->profil)
                                    <img src="{{ asset($admin->profil) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-800">
                                        {{ $admin->name }}
                                        @if($admin->id === Auth::id())
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 ml-1">VOUS</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $admin->matricule }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="text-sm text-slate-600 flex flex-col gap-1">
                                <span class="flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg> {{ $admin->email }}</span>
                                <span class="flex items-center gap-1.5 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.68411.498 1.498 0 00.681.947l4.317 4.317 4.317a1 1 0 01.947.681l.684.948A2 2 0 003 5z" /></svg> {{ $admin->phone ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="p-4 hidden md:table-cell">
                            <div class="text-sm text-slate-600">
                                <div>{{ $admin->gender ?? 'Non spécifié' }}</div>
                                <div class="text-xs text-slate-400">{{ $admin->created_at->format('d/m/Y') }}</div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $admin->status === 'Actif' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800' }}">
                                {{ $admin->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.admins.show', $admin->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-xl transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></a>
                                
                                @if($admin->id !== Auth::id())
                                <form action="{{ route('admin.admins.toggle-status', $admin->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-1.5 {{ $admin->status === 'Actif' ? 'text-slate-400 hover:text-slate-600' : 'text-green-600 hover:text-green-800' }}" title="{{ $admin->status === 'Actif' ? 'Désactiver' : 'Activer' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($admin->status === 'Actif')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @endif
                                        </svg>
                                    </button>
                                </form>
                                <div x-data="{ deleteAdmin{{ $admin->id }}Open: false }">
                                    <button type="button" @click="deleteAdmin{{ $admin->id }}Open = true" class="p-1.5 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>

                                    <x-confirm-modal 
                                        id="deleteAdmin{{ $admin->id }}"
                                        title="Supprimer l'administrateur"
                                        message="Voulez-vous vraiment supprimer l'accès de {{ $admin->name }} ? Cette action est définitive."
                                        confirmText="Oui, supprimer"
                                    />

                                    <form id="form-deleteAdmin{{ $admin->id }}" x-ref="formdeleteAdmin{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $admins->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="bg-slate-50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Aucun administrateur trouvé</h3>
            <p class="text-slate-500 mt-2">Essayez de modifier vos critères de recherche.</p>
        </div>
        @endif
    </div>
</div>
@endsection

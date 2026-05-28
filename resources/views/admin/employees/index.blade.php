@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Employés')
@section('page-title', 'Gestion des Employés')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backToDashboard="true" />
    </div>
    
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
    .admin-row {
        transition: background-color 0.2s;
        border-radius: 8px;
    }
    .admin-row:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Gestion des Employés</h2>
            <p class="text-slate-500">Administration - Gestion du personnel</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between card-stat">
            <div>
                <h4 class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</h4>
                <p class="text-slate-500 text-sm">Total Employés</p>
            </div>
            <div class="text-blue-500 bg-white p-2 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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
            <h3 class="font-bold text-lg text-slate-800">Liste des Employés</h3>
        </div>
        <div class="flex gap-2">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="px-4 py-2 border border-blue-200 text-blue-600 rounded-xl hover:bg-blue-50 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Exporter
                </button>
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-10" style="display: none;">
                    <form action="{{ route('admin.employees.download-pdf') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            PDF
                        </button>
                    </form>
                    <form action="{{ route('admin.employees.download-excel') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                             Excel
                        </button>
                    </form>
                </div>
            </div>

            <a href="{{ route('admin.employees.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center gap-2 shadow-lg shadow-green-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvel Employé
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="filter-section p-4 mb-6">
            <form action="{{ route('admin.employees.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
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
                    <select name="status" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="Actif" {{ request('status') == 'Actif' ? 'selected' : '' }}>Actif</option>
                        <option value="Inactif" {{ request('status') == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="Suspendu" {{ request('status') == 'Suspendu' ? 'selected' : '' }}>Suspendu</option>
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

        @if($employees->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-500 text-sm uppercase tracking-wider bg-slate-50">
                        <th class="p-4 font-semibold">Employé</th>
                        <th class="p-4 font-semibold">Contact</th>
                        <th class="p-4 font-semibold hidden md:table-cell">Infos</th>
                        <th class="p-4 font-semibold text-center">Statut</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-slate-50 transition-colors admin-row">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($employee->profil)
                                    <img src="{{ asset($employee->profil) }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-sm shadow-sm">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-slate-800">
                                        {{ $employee->name }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $employee->matricule }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="text-sm text-slate-600 flex flex-col gap-1">
                                <span class="flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg> {{ $employee->email }}</span>
                                <span class="flex items-center gap-1.5 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.68411.498 1.498 0 00.681.947l4.317 4.317 4.317a1 1 0 01.947.681l.684.948A2 2 0 003 5z" /></svg> {{ $employee->phone ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="p-4 hidden md:table-cell">
                            <div class="text-sm text-slate-600">
                                <div>{{ $employee->gender ?? 'Non spécifié' }}</div>
                                <div class="text-xs text-slate-400">{{ $employee->created_at->format('d/m/Y') }}</div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status === 'Actif' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-800' }}">
                                {{ $employee->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.employees.tasks', $employee->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Voir les tâches">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.employees.show', $employee->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Détails (Profil)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-xl transition-colors" title="Modifier"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></a>
                                
                                <form action="{{ route('admin.employees.toggle-status', $employee->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-1.5 {{ $employee->status === 'Actif' ? 'text-slate-400 hover:text-slate-600' : 'text-green-600 hover:text-green-800' }}" title="{{ $employee->status === 'Actif' ? 'Désactiver' : 'Activer' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($employee->status === 'Actif')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @endif
                                        </svg>
                                    </button>
                                </form>
                                <div x-data="{ deleteEmployee{{ $employee->id }}Open: false }">
                                    <button type="button" @click="deleteEmployee{{ $employee->id }}Open = true" class="p-1.5 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>

                                    <x-confirm-modal 
                                        id="deleteEmployee{{ $employee->id }}"
                                        title="Supprimer l'employé"
                                        message="Voulez-vous vraiment supprimer l'employé {{ $employee->name }} ? Cette action supprimera également toutes ses données rattachées."
                                        confirmText="Oui, supprimer"
                                    />
                                    
                                    <form id="form-deleteEmployee{{ $employee->id }}" action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @foreach($employees as $employee)
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $employees->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="bg-slate-50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Aucun employé trouvé</h3>
            <p class="text-slate-500 mt-2">Commencez par en ajouter un ou modifiez vos filtres.</p>
        </div>
        @endif
    </div>
</div>
@endsection

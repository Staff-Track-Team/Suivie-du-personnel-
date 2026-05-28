@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Projets')
@section('page-title', 'Gestion des Projets')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backToDashboard="true" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Projets</h2>
            <p class="text-slate-500">Suivi et pilotage des projets</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 flex items-center justify-between">
            <div>
                <h4 class="text-2xl font-bold text-indigo-600">{{ $stats['total'] }}</h4>
                <p class="text-slate-500 text-sm">Total Projets</p>
            </div>
            <div class="text-indigo-500 bg-white p-2 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
            <div>
                <h4 class="text-2xl font-bold text-blue-600">{{ $stats['encours'] }}</h4>
                <p class="text-slate-500 text-sm">En cours</p>
            </div>
            <div class="text-blue-500 bg-white p-2 rounded-full shadow-sm">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="bg-green-50 p-4 rounded-xl border border-green-100 flex items-center justify-between">
            <div>
                <h4 class="text-2xl font-bold text-green-600">{{ $stats['termines'] }}</h4>
                <p class="text-slate-500 text-sm">Terminés</p>
            </div>
            <div class="text-green-500 bg-white p-2 rounded-full shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-lg text-slate-800">Liste des projets</h3>
        <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-lg shadow-indigo-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouveau Projet
        </a>
    </div>

    <div class="p-6">
        <!-- Filtres -->
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
            <form action="{{ route('admin.projects.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher code, nom..." class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Status: Tous</option>
                        <option value="En attente" {{ request('status') == 'En attente' ? 'selected' : '' }}>En attente</option>
                        <option value="En cours" {{ request('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                        <option value="Terminé" {{ request('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                        <option value="Suspendu" {{ request('status') == 'Suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>
                <div>
                    <select name="priority" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Priorité: Toutes</option>
                        <option value="Basse" {{ request('priority') == 'Basse' ? 'selected' : '' }}>Basse</option>
                        <option value="Moyenne" {{ request('priority') == 'Moyenne' ? 'selected' : '' }}>Moyenne</option>
                        <option value="Haute" {{ request('priority') == 'Haute' ? 'selected' : '' }}>Haute</option>
                        <option value="Urgente" {{ request('priority') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full py-2 bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition-colors">Filtrer</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-500 text-xs uppercase tracking-wider bg-slate-50">
                        <th class="p-4 font-semibold">Projet</th>
                        <th class="p-4 font-semibold">Dates</th>
                        <th class="p-4 font-semibold text-center">Priorité</th>
                        <th class="p-4 font-semibold text-center">Statut</th>
                        <th class="p-4 font-semibold text-center">Progression</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $project)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($project->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $project->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $project->code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="text-xs text-slate-600">
                                <span class="block">Début: {{ $project->start_date->format('d/m/Y') }}</span>
                                <span class="block text-slate-400">Fin: {{ $project->end_date->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            @php
                                $prioColors = [
                                    'Basse' => 'bg-slate-100 text-slate-600',
                                    'Moyenne' => 'bg-blue-100 text-blue-600',
                                    'Haute' => 'bg-orange-100 text-orange-600',
                                    'Urgente' => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $prioColors[$project->priority] ?? 'bg-slate-100' }}">
                                {{ $project->priority }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                             @php
                                $statusColors = [
                                    'En attente' => 'bg-slate-100 text-slate-600',
                                    'En cours' => 'bg-blue-100 text-blue-600',
                                    'Terminé' => 'bg-green-100 text-green-600',
                                    'Suspendu' => 'bg-yellow-100 text-yellow-600',
                                    'Annulé' => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusColors[$project->status] ?? 'bg-slate-100' }}">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @php
                                $completion = $project->tasks_count > 0 ? round(($project->completed_tasks_count / $project->tasks_count) * 100) : 0;
                            @endphp
                            <div class="flex flex-col items-center gap-1">
                                <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width: {{ $completion }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">{{ $completion }}%</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.projects.show', $project->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Détails">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('admin.projects.edit', $project->id) }}" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <div x-data="{ deleteProject{{ $project->id }}Open: false }">
                                    <button type="button" @click="deleteProject{{ $project->id }}Open = true" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>

                                    <x-confirm-modal 
                                        id="deleteProject{{ $project->id }}"
                                        title="Supprimer le projet"
                                        message="Voulez-vous vraiment supprimer le projet {{ $project->name }} ? Cette action supprimera également toutes les tâches associées."
                                        confirmText="Oui, supprimer"
                                    />

                                    <form id="form-deleteProject{{ $project->id }}" action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-500">
                            Aucun projet trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection

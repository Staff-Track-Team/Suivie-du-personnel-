@extends('layouts.app-with-sidebar')

@section('title', 'Toutes les Tâches')
@section('page-title', 'Toutes les Tâches')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backToDashboard="true" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Tâches</h2>
                <p class="text-slate-500">Vue globale de toutes les tâches assignées.</p>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-lg shadow-indigo-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle Tâche
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100 flex items-center justify-between shadow-sm hover:shadow-md transition-all group">
            <div>
                <h4 class="text-3xl font-black text-indigo-600 tracking-tight">{{ $stats['total'] }}</h4>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">Total Tâches</p>
            </div>
            <div class="text-indigo-500 bg-white p-3 rounded-2xl shadow-sm group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 flex items-center justify-between shadow-sm hover:shadow-md transition-all group">
            <div>
                <h4 class="text-3xl font-black text-emerald-600 tracking-tight">{{ $stats['completed'] }}</h4>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">Terminées</p>
            </div>
            <div class="text-emerald-500 bg-white p-3 rounded-2xl shadow-sm group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="bg-rose-50 p-5 rounded-2xl border border-rose-100 flex items-center justify-between shadow-sm hover:shadow-md transition-all group">
            <div>
                <h4 class="text-3xl font-black text-rose-600 tracking-tight">{{ $stats['urgent'] }}</h4>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">Urgentes en cours</p>
            </div>
            <div class="text-rose-500 bg-white p-3 rounded-2xl shadow-sm group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm mb-6">
        <form action="{{ route('admin.tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Statut: Tous</option>
                    <option value="À faire" {{ request('status') == 'À faire' ? 'selected' : '' }}>À faire</option>
                    <option value="En cours" {{ request('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                    <option value="En attente" {{ request('status') == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Terminé" {{ request('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
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
                <select name="project_id" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Projet: Tous</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition-colors">Filtrer</button>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        @forelse($tasks->groupBy('project_id') as $projectId => $projectTasks)
        @php $project = $projectTasks->first()->project; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ open: true }">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between cursor-pointer group/header" @click="open = !open">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg group-hover/header:bg-indigo-600 group-hover/header:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $project ? $project->name : 'Sans Projet' }}</h3>
                        <p class="text-xs text-slate-500 font-mono">{{ $project ? $project->code : '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.projects.show', $projectId) }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700 uppercase tracking-widest" @click.stop>Détails Projet</a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 transition-transform duration-300" :class="{ 'rotate-180': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            
            <div x-show="open" x-collapse>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-50 text-slate-400 text-[10px] uppercase tracking-[0.1em] bg-white">
                                <th class="p-4 font-bold">Tâche</th>
                                <th class="p-4 font-bold">Assigné à</th>
                                <th class="p-4 font-bold text-center">Priorité</th>
                                <th class="p-4 font-bold text-center">Statut</th>
                                <th class="p-4 font-bold text-center">Date Fin</th>
                                <th class="p-4 font-bold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($projectTasks as $task)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="p-4">
                                    <div class="font-medium text-slate-800">{{ $task->title }}</div>
                                </td>
                                <td class="p-4 text-sm text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                            {{ $task->assignee ? substr($task->assignee->name, 0, 1) : '?' }}
                                        </div>
                                        {{ $task->assignee ? $task->assignee->name : 'Non assigné' }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $task->priority == 'Urgente' ? 'bg-red-100 text-red-600' : ($task->priority == 'Haute' ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                 <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $task->status == 'Terminé' ? 'bg-green-500' : ($task->status == 'En cours' ? 'bg-blue-500' : 'bg-slate-300') }}"></span>
                                        <span class="text-xs font-medium">{{ $task->status }}</span>
                                    </div>
                                </td>
                                 <td class="p-4 text-center text-xs text-slate-500">
                                    {{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.tasks.show', $task->id) }}" class="p-1 text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.tasks.edit', $task->id) }}" class="p-1 text-slate-400 hover:text-orange-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                        <div x-data="{ deleteTask{{ $task->id }}Open: false }" class="inline">
                                            <button type="button" @click="deleteTask{{ $task->id }}Open = true" class="p-1 text-slate-400 hover:text-red-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <x-confirm-modal 
                                                id="deleteTask{{ $task->id }}"
                                                title="Supprimer la tâche"
                                                message="Voulez-vous vraiment supprimer la tâche '{{ $task->title }}' ? Cette action est irréversible."
                                                confirmText="Oui, supprimer"
                                            />
                                            
                                            <form id="form-deleteTask{{ $task->id }}" action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Aucune tâche</h3>
            <p class="text-slate-500">Aucune tâche ne correspond à vos critères de recherche.</p>
        </div>
        @endforelse

        <div class="mt-8">
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection

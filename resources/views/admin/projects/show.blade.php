@extends('layouts.admin')

@section('title', 'Détails Projet')

@section('content')
<div class="h-full w-full">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.projects.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">{{ $project->name }}</h1>
            </div>
            <div class="flex items-center gap-3 ml-10">
                <span class="font-mono text-sm text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $project->code }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $project->status == 'En cours' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                    {{ $project->status }}
                </span>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.projects.download-tasks-pdf', $project->id) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exporter PDF
            </a>
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Dashboard Content (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Stats Rapides -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-slate-500 text-xs font-bold uppercase mb-1">Progression</div>
                    <div class="flex items-end justify-between">
                        <span class="text-2xl font-bold text-slate-800">{{ $completion }}%</span>
                        <div class="w-12 h-12">
                            <svg class="transform -rotate-90 w-full h-full" viewBox="0 0 36 36">
                                <path class="text-slate-100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                                <path class="text-indigo-600" stroke-dasharray="{{ $completion }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="4" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-slate-500 text-xs font-bold uppercase mb-1">Tâches</div>
                    <div class="text-2xl font-bold text-slate-800">{{ $taskStats['total'] }}</div>
                    <div class="text-xs text-slate-400 mt-1">{{ $taskStats['completed'] }} terminées</div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                    <div class="text-slate-500 text-xs font-bold uppercase mb-1">Délai</div>
                    @php
                        $daysDiff = (int)now()->diffInDays($project->end_date, false);
                    @endphp
                    <div class="text-2xl font-bold {{ $daysDiff < 0 ? 'text-red-500' : 'text-slate-800' }}">
                        {{ abs($daysDiff) }}j
                    </div>
                    <div class="text-xs text-slate-400 mt-1">{{ $daysDiff < 0 ? 'de retard' : 'restants' }}</div>
                </div>
            </div>

            <!-- Liste des Tâches -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ taskModalOpen: false }">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800">Tâches du projet</h3>
                    <button @click="taskModalOpen = true" class="text-sm px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter Tâche
                    </button>
                </div>
                <div class="p-0">
                    @forelse($project->tasks as $task)
                    <div class="p-4 border-b border-slate-100 hover:bg-slate-50 transition-colors flex items-center justify-between last:border-0 group">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full {{ $task->status == 'Terminé' ? 'bg-green-500' : ($task->status == 'En cours' ? 'bg-blue-500' : 'bg-slate-300') }}"></div>
                            <div>
                                <div class="font-medium text-slate-800">{{ $task->title }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $task->assignee ? $task->assignee->name : 'Non assigné' }} • 
                                    Fin: {{ $task->end_date ? $task->end_date->format('d/m') : '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $task->priority == 'Urgente' ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-600' }}">{{ $task->priority }}</span>
                            <!-- Actions Tâche (Edit/Delete) -->
                            <div x-data="{ deleteTask{{ $task->id }}Open: false }">
                                <a href="{{ route('admin.tasks.edit', $task->id) }}" class="p-1 text-slate-400 hover:text-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                
                                <button type="button" @click="deleteTask{{ $task->id }}Open = true" class="p-1 text-slate-400 hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>

                                <x-confirm-modal 
                                    id="deleteTask{{ $task->id }}"
                                    title="Supprimer la tâche"
                                    message="Voulez-vous vraiment supprimer la tâche '{{ $task->title }}' ?"
                                    confirmText="Oui, supprimer"
                                />

                                <form id="form-deleteTask{{ $task->id }}" x-ref="formdeleteTask{{ $task->id }}" action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500">
                        Aucune tâche pour le moment.
                        <button @click="taskModalOpen = true" class="text-indigo-600 hover:underline">Créer la première</button>
                    </div>
                    @endforelse
                </div>

                <!-- Modal Création Tâche -->
                <div x-show="taskModalOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="taskModalOpen = false"></div>

                        <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl transform transition-all">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Nouvelle Tâche</h3>
                            
                            <form action="{{ route('admin.tasks.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Titre <span class="text-red-500">*</span></label>
                                        <input type="text" name="title" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                        <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Début (Info)</label>
                                            <input type="date" name="start_date" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Fin (Info)</label>
                                            <input type="date" name="end_date" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Priorité <span class="text-red-500">*</span></label>
                                            <select name="priority" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="Moyenne">Moyenne</option>
                                                <option value="Basse">Basse</option>
                                                <option value="Haute">Haute</option>
                                                <option value="Urgente">Urgente</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Assigner à</label>
                                            <select name="assigned_to" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">-- Personne --</option>
                                                @foreach(\App\Models\User::where('is_admin', false)->where('status', 'Actif')->get() as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" @click="taskModalOpen = false" class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium transition-colors">Annuler</button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">Créer la tâche</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info (1/3) -->
        <div class="space-y-6">
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-4">Informations</h3>
                <ul class="space-y-4 text-sm">
                    <li>
                        <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Budget</span>
                        <div class="font-mono">{{ $project->budget ? number_format($project->budget, 0, ',', ' ') . ' FCFA' : 'Non défini' }}</div>
                    </li>
                    <li>
                        <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Dates</span>
                        <div class="text-slate-700">{{ $project->start_date->format('d/m/Y') }} → {{ $project->end_date->format('d/m/Y') }}</div>
                    </li>
                    <li>
                        <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Priorité</span>
                        <div class="text-slate-700">{{ $project->priority }}</div>
                    </li>
                    <li>
                        <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Description</span>
                        <div class="text-slate-600 leading-relaxed">{{ $project->description ?? 'Aucune description.' }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

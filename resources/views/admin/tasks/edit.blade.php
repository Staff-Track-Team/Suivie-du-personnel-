@extends('layouts.app-with-sidebar')

@section('title', 'Modifier Tâche')
@section('page-title', 'Modifier Tâche')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('admin.tasks.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.show', $task->project_id) }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Modifier Tâche</h1>
            </div>
            <p class="text-slate-500 ml-10">Projet : <strong>{{ $task->project->name }}</strong></p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <div>
            <h3 class="font-bold text-red-800">Erreurs</h3>
            <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Formulaire Modification (2/3) -->
        <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST" class="lg:col-span-2 space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-indigo-50/50 border-b border-indigo-100 flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Détails de la tâche</h2>
                </div>
                
                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Titre</label>
                        <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Priorité</label>
                            <select name="priority" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['Basse', 'Moyenne', 'Haute', 'Urgente'] as $prio)
                                    <option value="{{ $prio }}" {{ old('priority', $task->priority) == $prio ? 'selected' : '' }}>{{ $prio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Statut</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['À faire', 'En cours', 'En attente', 'Terminé'] as $status)
                                    <option value="{{ $status }}" {{ old('status', $task->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date de début</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $task->start_date ? $task->start_date->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date de fin</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $task->end_date ? $task->end_date->format('Y-m-d') : '') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Assignation</label>
                        <select name="assigned_to" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Non assigné --</option>
                            @foreach(\App\Models\User::where('is_admin', false)->where('status', 'Actif')->get() as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-yellow-700 mt-2">⚠️ Changer l'assignation enverra une notification par email aux utilisateurs concernés.</p>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30">
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Historique / Audit (1/3) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Historique d'activité</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-4 border-l-2 border-slate-100 space-y-6">
                        @forelse(\App\Models\TaskAudit::where('task_id', $task->id)->latest()->get() as $audit)
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-slate-300 border-2 border-white"></div>
                            <p class="text-xs text-slate-400 mb-0.5">{{ $audit->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-sm font-medium text-slate-800">
                                <span class="text-indigo-600">{{ $audit->user ? $audit->user->name : 'Système' }}</span>
                                @if($audit->action == 'created')
                                    a créé la tâche.
                                @elseif($audit->action == 'updated')
                                    a mis à jour la tâche.
                                @else
                                    {{ $audit->action }}
                                @endif
                            </p>
                            @if($audit->old_status && $audit->new_status && $audit->old_status != $audit->new_status)
                                <div class="mt-1 text-xs bg-slate-50 p-2 rounded border border-slate-100 inline-block">
                                    Statut: <span class="line-through text-slate-400">{{ $audit->old_status }}</span> 
                                    → <span class="font-bold text-slate-700">{{ $audit->new_status }}</span>
                                </div>
                            @endif
                        </div>
                        @empty
                        <p class="text-sm text-slate-400 italic">Aucun historique disponible.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Détails Tâche')

@section('content')
<div class="h-full w-full">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.tasks.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">{{ $task->title }}</h1>
            </div>
            <div class="flex items-center gap-3 ml-10">
                <a href="{{ route('admin.projects.show', $task->project_id) }}" class="font-mono text-sm text-indigo-600 hover:underline bg-indigo-50 px-2 py-0.5 rounded">{{ $task->project->name }}</a>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">{{ $task->status }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">{{ $task->priority }}</span>
            </div>
        </div>
        
        <div class="flex gap-2" x-data="{ deleteTaskOpen: false }">
            <a href="{{ route('admin.tasks.edit', $task->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Modifier
            </a>
            
            <button type="button" @click="deleteTaskOpen = true" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Supprimer
            </button>

            <x-confirm-modal 
                id="deleteTask"
                title="Supprimer la tâche"
                message="Voulez-vous vraiment supprimer définitivement cette tâche ?"
                confirmText="Oui, supprimer"
            />

            <form id="form-deleteTask" x-ref="formdeleteTask" action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        
        <!-- Détails -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Informations</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Description</span>
                        <div class="text-slate-700 leading-relaxed">{{ $task->description ?? 'Aucune description.' }}</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                        <div>
                             <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Assigné à</span>
                             <div class="flex items-center gap-2">
                                 @if($task->assignee)
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                        {{ substr($task->assignee->name, 0, 1) }}
                                    </div>
                                    <span class="text-slate-700 font-medium">{{ $task->assignee->name }}</span>
                                 @else
                                    <span class="text-slate-400 italic">Non assigné</span>
                                 @endif
                             </div>
                        </div>
                        <div>
                             <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Créé par</span>
                             <div class="text-slate-600">{{ $task->creator ? $task->creator->name : 'Système' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                        <div>
                             <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Date Début</span>
                             <div class="text-slate-700">{{ $task->start_date ? $task->start_date->format('d/m/Y') : '-' }}</div>
                        </div>
                         <div>
                             <span class="block text-xs uppercase font-bold text-slate-400 mb-1">Date Fin</span>
                             <div class="text-slate-700">{{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Historique d'Audits</h3>
                </div>
                <div class="p-6">
                     <div class="relative pl-4 border-l-2 border-slate-100 space-y-6">
                        @forelse($audits as $audit)
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-slate-300 border-2 border-white"></div>
                            <p class="text-xs text-slate-400 mb-0.5">{{ $audit->created_at->format('d/m/Y H:i') }}</p>
                            <div class="text-sm text-slate-800">
                                <span class="font-bold text-indigo-600">{{ $audit->user ? $audit->user->name : 'Système' }}</span>
                                <span class="text-slate-600">
                                    @if($audit->action == 'created') a créé la tâche.
                                    @elseif($audit->action == 'updated') a mis à jour la tâche.
                                    @else {{ $audit->action }}
                                    @endif
                                </span>
                            </div>
                            @if($audit->action !== 'status_change' && $audit->formatted_details && $audit->formatted_details !== '-')
                                <p class="text-[10px] text-slate-500 mt-1 italic leading-tight">{{ $audit->formatted_details }}</p>
                            @endif
                            @if($audit->old_status && $audit->new_status && $audit->old_status != $audit->new_status)
                                <div class="mt-1 text-xs bg-gray-50 p-1.5 rounded inline-block border border-gray-100">
                                    <span class="line-through text-gray-400">{{ $audit->old_status }}</span> 
                                    → <span class="font-bold text-gray-700">{{ $audit->new_status }}</span>
                                </div>
                            @endif
                        </div>
                        @empty
                        <p class="text-sm text-slate-400 italic">Aucune activité enregistrée.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.employee')

@section('title', 'Détails de la Tâche')

@section('content')
    <div class="mb-8">
        <a href="{{ route('employee.tasks.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Task Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $task->title }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase
                        {{ $task->status === 'Terminé' ? 'bg-emerald-100 text-emerald-800' : 
                           ($task->status === 'En cours' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $task->status }}
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Description</h3>
                    <div class="text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 font-medium">
                        {{ $task->description ?: 'Aucune description fournie.' }}
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Priorité</p>
                        <p class="text-center font-bold text-slate-800">{{ $task->priority }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Début</p>
                        <p class="text-center font-bold text-slate-800">{{ $task->start_date ? $task->start_date->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Fin prévue</p>
                        <p class="text-center font-bold text-slate-800">{{ $task->end_date ? $task->end_date->format('d M Y') : '-' }}</p>
                    </div>
                     <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Projet</p>
                        <p class="text-center font-bold text-blue-600 underline decoration-blue-200 underline-offset-4">{{ $task->project->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Activity / History -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-black text-slate-800 tracking-tight">Historique de la tâche</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($audits as $audit)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                @if(!$loop->last)
                                <div class="w-0.5 h-full bg-slate-100 my-1"></div>
                                @endif
                            </div>
                            <div class="pb-6">
                                <p class="text-sm font-bold text-slate-800">
                                    {{ $audit->user->name }} 
                                    <span class="font-normal text-slate-500">
                                        @if($audit->action === 'status_change')
                                            a changé le statut de <span class="font-medium text-slate-700">{{ $audit->old_status }}</span> à <span class="font-medium text-blue-600">{{ $audit->new_status }}</span>
                                        @elseif($audit->action === 'created')
                                            a créé la tâche
                                        @elseif($audit->action === 'updated')
                                            a mis à jour la tâche
                                        @else
                                            {{ $audit->action }}
                                        @endif
                                    </span>
                                </p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $audit->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-slate-400 text-sm italic">Aucun historique disponible.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Update Status -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden p-6 sticky top-6">
                <h3 class="text-lg font-black text-slate-800 mb-6 tracking-tight">Mettre à jour le statut</h3>
                
                @if($task->status === 'Terminé')
                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-emerald-800">Mission accomplie !</p>
                            <p class="text-xs text-emerald-600 mt-0.5 leading-relaxed font-medium">Cette tâche est marquée comme terminée et ne peut plus être modifiée.</p>
                        </div>
                    </div>
                @else
                    <form action="{{ route('employee.tasks.update', $task->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-2">
                            @foreach(['À faire', 'En cours', 'En attente', 'Terminé'] as $label)
                            <label class="flex items-center gap-3 p-3 rounded-xl border {{ $task->status === $label ? 'border-blue-500 bg-blue-50/50' : 'border-slate-100 hover:bg-slate-50' }} cursor-pointer transition-all group">
                                <input type="radio" name="status" value="{{ $label }}" {{ $task->status === $label ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-200">
                                <span class="text-sm font-bold {{ $task->status === $label ? 'text-blue-700' : 'text-slate-600 group-hover:text-slate-900' }}">{{ $label }}</span>
                                @if($task->status === $label)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 ml-auto" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                @endif
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full mt-4 py-3 px-4 bg-blue-600 text-white font-black uppercase text-[10px] tracking-widest rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 active:scale-95">
                            Mettre à jour
                        </button>
                    </form>
                @endif

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Responsable du projet</p>
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 tracking-tight">{{ $task->project->creator->name }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Administrateur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

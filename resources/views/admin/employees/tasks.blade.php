@extends('layouts.admin')

@section('title', 'Tâches de l\'employé')

@section('content')
<div class="h-full w-full">
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.employees.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Tâches assignées à {{ $employee->name }}</h1>
            </div>
            <p class="text-slate-500 ml-10">Visualisation des responsabilités par projet.</p>
        </div>
    </div>

    @php
        $totalTasks = 0;
        foreach($tasks as $projectTasks) {
            $totalTasks += $projectTasks->count();
        }
    @endphp

    @if($totalTasks > 0)
    <div class="space-y-6">
        @foreach($tasks as $projectId => $projectTasks)
        @php $project = $projectTasks->first()->project; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ open: true }">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between cursor-pointer group/header" @click="open = !open">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg group-hover/header:bg-blue-600 group-hover/header:text-white transition-colors">
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
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $projectTasks->count() }} tâches</span>
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
                                <td class="p-4 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $task->priority == 'Urgente' ? 'bg-red-100 text-red-600' : ($task->priority == 'Haute' ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                 <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $task->status == 'Terminé' ? 'bg-green-500' : ($task->status == 'En cours' ? 'bg-blue-500' : 'bg-slate-300') }}"></span>
                                        <span class="text-xs font-medium text-slate-700">{{ $task->status }}</span>
                                    </div>
                                </td>
                                 <td class="p-4 text-center text-xs text-slate-500 font-medium">
                                    {{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.tasks.show', $task->id) }}" class="p-1 text-slate-400 hover:text-blue-600 transition-colors" title="Détails">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.tasks.edit', $task->id) }}" class="p-1 text-slate-400 hover:text-orange-600 transition-colors" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-20 text-center">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-1">Aucune tâche assignée</h3>
        <p class="text-slate-500">Cet employé n'a pas encore de tâches assignées dans le système.</p>
        <div class="mt-6">
            <a href="{{ route('admin.tasks.create') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/20 inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Assigner une tâche
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

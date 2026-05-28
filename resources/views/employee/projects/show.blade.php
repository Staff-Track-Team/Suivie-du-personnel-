@extends('layouts.app-with-sidebar')

@section('title', 'Détails du Projet')
@section('page-title', 'Détails du Projet')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('employee.projects.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $project->name }}</h2>
                <p class="text-slate-500">Détails et tâches du projet</p>
            </div>
        </div>
    </div>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $project->name }}</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">{{ $project->code }}</p>
            </div>
        </div>
        <div class="bg-white px-4 py-2 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
             <div class="text-right">
                 <p class="text-[10px] font-black text-slate-400 uppercase">Votre progression</p>
                 <p class="text-sm font-black text-blue-600">{{ $project->my_progress }}%</p>
             </div>
             <div class="w-10 h-10 rounded-full border-4 border-slate-50 flex items-center justify-center relative overflow-hidden">
                <svg class="w-full h-full -rotate-90">
                    <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="4" fill="transparent" class="text-slate-100" />
                    <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="4" fill="transparent" class="text-blue-600" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $project->my_progress }}" />
                </svg>
             </div>
        </div>
    </div>

    <!-- Project description or info can go here if needed -->

    <div class="bg-slate-50/50 rounded-[2.5rem] shadow-sm border border-white overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/30 flex justify-between items-center">
            <h3 class="font-black text-slate-800 tracking-tight">Vos tâches sur ce projet</h3>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $myTasks->count() }} tâches</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50 text-slate-400 text-[10px] uppercase tracking-[0.1em] bg-white">
                        <th class="p-6 font-black">Titre</th>
                        <th class="p-6 font-black text-center">Priorité</th>
                        <th class="p-6 font-black text-center">Date limite</th>
                        <th class="p-6 font-black text-center">Statut</th>
                        <th class="p-6 font-black text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($myTasks as $task)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-6">
                            <span class="font-bold text-slate-700">{{ $task->title }}</span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-black 
                                {{ $task->priority === 'Urgente' ? 'bg-red-50 text-red-600' : 
                                   ($task->priority === 'Haute' ? 'bg-orange-50 text-orange-600' : 
                                   ($task->priority === 'Moyenne' ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-600')) }}">
                                {{ $task->priority }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="text-xs font-bold text-slate-500">
                                {{ $task->end_date ? $task->end_date->format('d/m/Y') : 'Non définie' }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black 
                                {{ $task->status === 'Terminé' ? 'bg-emerald-100 text-emerald-700' : 
                                   ($task->status === 'En cours' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <a href="{{ route('employee.tasks.show', $task->id) }}" class="p-3 bg-slate-50 rounded-2xl text-slate-400 hover:text-blue-600 hover:bg-white shadow-sm transition-all inline-block border border-transparent hover:border-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

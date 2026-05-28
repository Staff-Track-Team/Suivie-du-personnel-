@extends('layouts.app-with-sidebar')

@section('title', 'Mes Tâches')
@section('page-title', 'Mes Tâches')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Mes Tâches</h2>
                <p class="text-slate-500">Liste complète des missions qui vous sont confiées.</p>
            </div>
        </div>
    </div>

        <div class="bg-white p-2 rounded-xl border border-slate-100 shadow-sm flex items-center gap-2">
            <form action="{{ route('employee.tasks.index') }}" method="GET" class="flex items-center gap-2">
                <select name="status" class="text-sm rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-1.5" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    @foreach(['À faire', 'En cours', 'En attente', 'Terminé'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-50/50 p-5 rounded-3xl shadow-sm border border-white flex flex-col justify-between group hover:border-blue-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total</span>
            </div>
            <h4 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['total'] }}</h4>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-50 flex flex-col justify-between group hover:border-amber-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">À faire</span>
            </div>
            <h4 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['pending'] }}</h4>
        </div>

        <div class="bg-slate-50/50 p-5 rounded-3xl shadow-sm border border-white flex flex-col justify-between group hover:border-blue-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">En cours</span>
            </div>
            <h4 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['in_progress'] }}</h4>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-50 flex flex-col justify-between group hover:border-emerald-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Terminées</span>
            </div>
            <h4 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['completed'] }}</h4>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($tasks->groupBy('project_id') as $projectId => $projectTasks)
        @php $project = $projectTasks->first()->project; @endphp
        <div class="bg-slate-50/50 rounded-2xl shadow-sm border border-white overflow-hidden" x-data="{ open: true }">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between cursor-pointer group/header" @click="open = !open">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover/header:bg-blue-600 group-hover/header:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $project->name }}</h3>
                        <p class="text-xs text-slate-500 font-mono">{{ $project->code }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $projectTasks->count() }} tâches</span>
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
                                <th class="p-4 font-bold text-center">Échéance</th>
                                <th class="p-4 font-bold text-center">Statut</th>
                                <th class="p-4 font-bold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($projectTasks as $task)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="p-4">
                                    <span class="font-medium text-slate-900">{{ $task->title }}</span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold 
                                        {{ $task->priority === 'Urgente' ? 'bg-red-50 text-red-600' : 
                                           ($task->priority === 'Haute' ? 'bg-orange-50 text-orange-600' : 
                                           ($task->priority === 'Moyenne' ? 'bg-blue-50 text-blue-600' : 'bg-slate-50 text-slate-600')) }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-sm text-slate-500">
                                        {{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold 
                                        {{ $task->status === 'Terminé' ? 'bg-green-100 text-green-800' : 
                                           ($task->status === 'En cours' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('employee.tasks.show', $task->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        @empty
        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-slate-200">
             <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <p class="text-slate-500">Aucune tâche assignée ne correspond à vos critères.</p>
        </div>
        @endforelse

        @if($tasks->hasPages())
        <div class="mt-8">
            {{ $tasks->links() }}
        </div>
        @endif
    </div>
@endsection

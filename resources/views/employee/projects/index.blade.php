@extends('layouts.app-with-sidebar')

@section('title', 'Mes Projets')
@section('page-title', 'Mes Projets')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Mes Projets</h2>
                <p class="text-slate-500">Projets sur lesquels vous intervenez actuellement.</p>
            </div>
        </div>
    </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div class="bg-slate-50/50 rounded-3xl p-6 shadow-sm border border-white flex flex-col justify-between group hover:border-blue-200 transition-all">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-1 group-hover:text-blue-600 transition-colors">{{ $project->name }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mb-4">{{ $project->code }}</p>
                
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-slate-500 font-bold">Votre progression</span>
                    <span class="text-blue-600 font-black">{{ $project->my_progress }}%</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: {{ $project->my_progress }}%"></div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <div class="text-[10px] font-black text-slate-400 uppercase">
                    {{ $project->tasks_count }} tâche(s) assignée(s)
                </div>
                <a href="{{ route('employee.projects.show', $project->id) }}" class="p-2 bg-slate-50 rounded-xl text-slate-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <p class="text-slate-500 font-medium italic">Vous n'êtes assigné à aucune tâche sur un projet actif.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

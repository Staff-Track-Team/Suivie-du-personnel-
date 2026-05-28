@extends('layouts.app-with-sidebar')

@section('title', 'Nouvelle Tâche')
@section('page-title', 'Nouvelle Tâche')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('admin.tasks.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3">
                <a href="{{ route('admin.tasks.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Assigner une tâche</h1>
            </div>
            <p class="text-slate-500 ml-10">Création d'une nouvelle tâche pour un projet.</p>
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

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.tasks.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-indigo-50/50 border-b border-indigo-100 flex items-center gap-3">
                     <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Information de la tâche</h2>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Sélection Projet -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Projet concerné <span class="text-red-500">*</span></label>
                        <select name="project_id" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                             <option value="">-- Sélectionner un projet --</option>
                             @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }} ({{ $project->code }})</option>
                             @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Titre de la tâche <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ex: Rédiger le cahier des charges" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Détails de la tâche...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date de début (Info)</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date de fin (Info)</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Priorité</label>
                            <select name="priority" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach(['Basse', 'Moyenne', 'Haute', 'Urgente'] as $prio)
                                    <option value="{{ $prio }}" {{ old('priority', 'Moyenne') == $prio ? 'selected' : '' }}>{{ $prio }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Assigner à</label>
                            <select name="assigned_to" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Non assigné --</option>
                                @foreach(\App\Models\User::where('is_admin', false)->where('status', 'Actif')->get() as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 gap-3">
                         <a href="{{ route('admin.tasks.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors">
                            Annuler
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30">
                            Créer la tâche
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

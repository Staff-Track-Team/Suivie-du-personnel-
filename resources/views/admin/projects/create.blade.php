@extends('layouts.app-with-sidebar')

@section('title', 'Nouveau Projet')
@section('page-title', 'Nouveau Projet')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('admin.projects.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3">
                <a href="{{ route('admin.projects.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Nouveau projet</h1>
            </div>
            <p class="text-slate-500 ml-10">Création et planification d'un nouveau projet.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
            <h3 class="font-bold text-red-800">Erreurs de validation</h3>
            <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.projects.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        @csrf

        <!-- Informations Principales (2/3) -->
        <div class="space-y-6 lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-indigo-50/50 border-b border-indigo-100 flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Détails du projet</h2>
                </div>
                
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nom du projet <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Déploiement Fibre Optique Zone Nord" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Code Projet <span class="text-xs font-normal text-slate-500">(Auto si vide)</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="PRJ-2024-001" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-mono uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Budget (FCFA)</label>
                            <input type="number" name="budget" value="{{ old('budget') }}" placeholder="1000000" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Objectifs et contexte du projet...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-blue-50/50 border-b border-blue-100 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Planification</h2>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Date de début <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Date de fin prévue <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Latérale (1/3) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden p-6">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Statut initial <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="En attente" {{ old('status') == 'En attente' ? 'selected' : '' }}>En attente</option>
                        <option value="En cours" {{ old('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                        <option value="Terminé" {{ old('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                        <option value="Suspendu" {{ old('status') == 'Suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="Annulé" {{ old('status') == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Priorité <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="priority" value="Basse" class="text-indigo-600 focus:ring-indigo-500 border-slate-300" {{ old('priority') == 'Basse' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-slate-600">Basse</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="priority" value="Moyenne" class="text-indigo-600 focus:ring-indigo-500 border-slate-300" {{ old('priority', 'Moyenne') == 'Moyenne' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-slate-600">Moyenne</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="priority" value="Haute" class="text-indigo-600 focus:ring-indigo-500 border-slate-300" {{ old('priority') == 'Haute' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-slate-600">Haute</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="radio" name="priority" value="Urgente" class="text-indigo-600 focus:ring-indigo-500 border-slate-300" {{ old('priority') == 'Urgente' ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-slate-600 text-red-600">Urgente</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full px-6 py-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Créer le Projet
                </button>
                <a href="{{ route('admin.projects.index') }}" class="w-full px-6 py-4 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors text-center">
                    Annuler
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

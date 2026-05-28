@extends('layouts.app-with-sidebar')

@section('title', 'Créer un Employé')
@section('page-title', 'Créer un Employé')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Ajouter un nouvel employé</h1>
        <p class="text-gray-600">Remplissez les informations pour créer un nouveau compte employé</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-8 border border-white/20 shadow-lg">
            <form action="{{ route('hr.employees.store') }}" method="POST">
                @csrf

                <!-- Informations personnelles -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informations personnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email professionnel *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle *</label>
                            <select name="role" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Sélectionner un rôle</option>
                                <option value="employe" {{ old('role') == 'employe' ? 'selected' : '' }}>Employé</option>
                                <option value="manager_rh" {{ old('role') == 'manager_rh' ? 'selected' : '' }}>Manager RH</option>
                            </select>
                            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Informations professionnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Département *</label>
                            <input type="text" name="department" value="{{ old('department') }}" required
                                   placeholder="Ex: Informatique, Marketing, RH..."
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('department') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Poste *</label>
                            <input type="text" name="position" value="{{ old('position') }}" required
                                   placeholder="Ex: Développeur, Comptable, Assistant..."
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('position') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date d'embauche *</label>
                            <input type="date" name="hired_at" value="{{ old('hired_at') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('hired_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Salaire de base (FCFA) *</label>
                            <input type="number" name="salaire_base" value="{{ old('salaire_base') }}" required min="0" step="1000"
                                   placeholder="150000"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('salaire_base') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations du contrat -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Contrat
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de contrat *</label>
                            <select name="type_contrat" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Sélectionner un type</option>
                                <option value="CDI" {{ old('type_contrat') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option value="CDD" {{ old('type_contrat') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option value="Stage" {{ old('type_contrat') == 'Stage' ? 'selected' : '' }}>Stage</option>
                                <option value="Alternance" {{ old('type_contrat') == 'Alternance' ? 'selected' : '' }}>Alternance</option>
                                <option value="Freelance" {{ old('type_contrat') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            </select>
                            @error('type_contrat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div id="dateFinContainer" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin du contrat</label>
                            <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('date_fin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('hr.employees.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Créer l'employé
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeContrat = document.querySelector('select[name="type_contrat"]');
    const dateFinContainer = document.getElementById('dateFinContainer');
    const dateFinInput = document.querySelector('input[name="date_fin"]');

    function toggleDateFin() {
        if (typeContrat.value === 'CDD' || typeContrat.value === 'Stage' || typeContrat.value === 'Alternance') {
            dateFinContainer.style.display = 'block';
            dateFinInput.required = true;
        } else {
            dateFinContainer.style.display = 'none';
            dateFinInput.required = false;
            dateFinInput.value = '';
        }
    }

    typeContrat.addEventListener('change', toggleDateFin);
    toggleDateFin();
});
</script>
@endsection

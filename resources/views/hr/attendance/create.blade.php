@extends('layouts.app-with-sidebar')

@section('title', 'Nouveau Pointage')
@section('page-title', 'Nouveau Pointage')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('hr.attendance.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Nouveau Pointage</h2>
            <p class="text-gray-600 mt-1">Enregistrez les heures de présence d'un employé</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-8 border border-white/20 shadow-lg">
            <form action="{{ route('hr.attendance.store') }}" method="POST">
                @csrf

                <!-- Sélection de l'employé -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employé *</label>
                    <select name="user_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un employé</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} - {{ $employee->matricule }} ({{ $employee->employeeInfo->position ?? 'Non défini' }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Date -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', today()->format('Y-m-d')) }}" required max="{{ today()->format('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Statut -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select name="statut" required id="statutSelect" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sélectionner un statut</option>
                        <option value="Présent" {{ old('statut') == 'Présent' ? 'selected' : '' }}>Présent</option>
                        <option value="Retard" {{ old('statut') == 'Retard' ? 'selected' : '' }}>Retard</option>
                        <option value="Absent" {{ old('statut') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Congé" {{ old('statut') == 'Congé' ? 'selected' : '' }}>Congé</option>
                        <option value="Maladie" {{ old('statut') == 'Maladie' ? 'selected' : '' }}>Maladie</option>
                    </select>
                    @error('statut') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Motif d'absence (conditionnel) -->
                <div id="motifContainer" class="mb-6" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'absence *</label>
                    <textarea name="motif_absence" rows="3" placeholder="Expliquez la raison de l'absence..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('motif_absence') }}</textarea>
                    @error('motif_absence') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Heures de travail (conditionnel) -->
                <div id="heuresContainer" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure d'arrivée</label>
                            <input type="time" name="heure_arrivee" value="{{ old('heure_arrivee') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('heure_arrivee') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heure de départ</label>
                            <input type="time" name="heure_depart" value="{{ old('heure_depart') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('heure_depart') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Début de pause (optionnel)</label>
                            <input type="time" name="pause_debut" value="{{ old('pause_debut') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('pause_debut') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fin de pause (optionnel)</label>
                            <input type="time" name="pause_fin" value="{{ old('pause_fin') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('pause_fin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('hr.attendance.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Enregistrer le pointage
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pointage rapide (pour aujourd'hui) -->
    <div class="max-w-2xl mx-auto mt-6">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pointage rapide pour aujourd'hui</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($employees->take(3) as $employee)
                    @php
                        $alreadyCheckedIn = App\Models\Attendance::where('user_id', $employee->id)
                                                            ->where('date', today()->format('Y-m-d'))
                                                            ->exists();
                    @endphp
                    
                    @if ($alreadyCheckedIn)
                        <div class="bg-gray-50 p-4 rounded-lg opacity-75">
                            <div class="text-center mb-3">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-800">{{ $employee->name }}</p>
                                <p class="text-sm text-gray-500">{{ $employee->matricule }}</p>
                            </div>
                            
                            <div class="text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Déjà pointé
                                </span>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('hr.attendance.store') }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $employee->id }}">
                            <input type="hidden" name="date" value="{{ today()->format('Y-m-d') }}">
                            <input type="hidden" name="statut" value="Présent">
                            <input type="hidden" name="heure_arrivee" value="{{ now()->format('H:i') }}">
                            
                            <div class="text-center mb-3">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-2">
                                    <span class="text-green-600 font-medium">{{ substr($employee->name, 0, 1) }}</span>
                                </div>
                                <p class="font-medium text-gray-800">{{ $employee->name }}</p>
                                <p class="text-sm text-gray-500">{{ $employee->matricule }}</p>
                            </div>
                            
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm transition-colors">
                                Pointage présent
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statutSelect = document.getElementById('statutSelect');
    const motifContainer = document.getElementById('motifContainer');
    const heuresContainer = document.getElementById('heuresContainer');
    const motifTextarea = document.querySelector('textarea[name="motif_absence"]');

    function toggleFields() {
        const statut = statutSelect.value;
        
        if (statut === 'Absent' || statut === 'Maladie') {
            motifContainer.style.display = 'block';
            motifTextarea.required = true;
            heuresContainer.style.display = 'none';
            // Désactiver les champs d'heures
            document.querySelectorAll('#heuresContainer input').forEach(input => {
                input.required = false;
                input.disabled = true;
            });
        } else if (statut === 'Congé') {
            motifContainer.style.display = 'block';
            motifTextarea.required = false;
            heuresContainer.style.display = 'none';
            document.querySelectorAll('#heuresContainer input').forEach(input => {
                input.required = false;
                input.disabled = true;
            });
        } else {
            motifContainer.style.display = 'none';
            motifTextarea.required = false;
            heuresContainer.style.display = 'block';
            // Activer les champs d'heures
            document.querySelectorAll('#heuresContainer input').forEach(input => {
                input.disabled = false;
            });
        }
    }

    statutSelect.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endsection

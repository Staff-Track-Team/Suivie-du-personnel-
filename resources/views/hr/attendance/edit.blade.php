@extends('layouts.app-with-sidebar')

@section('title', 'Modifier le Pointage')
@section('page-title', 'Modifier le Pointage')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backToDashboard="true" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Modifier le Pointage</h2>
                <p class="text-gray-600">Modifier les informations de pointage pour {{ $attendance->user->name }}</p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
        <form action="{{ route('hr.attendance.update', $attendance) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employé -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employé *</label>
                    <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $employee->id == $attendance->user_id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->matricule }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" value="{{ $attendance->date->format('Y-m-d') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut *</label>
                    <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="Présent" {{ $attendance->statut == 'Présent' ? 'selected' : '' }}>Présent</option>
                        <option value="Retard" {{ $attendance->statut == 'Retard' ? 'selected' : '' }}>Retard</option>
                        <option value="Absent" {{ $attendance->statut == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Congé" {{ $attendance->statut == 'Congé' ? 'selected' : '' }}>Congé</option>
                        <option value="Maladie" {{ $attendance->statut == 'Maladie' ? 'selected' : '' }}>Maladie</option>
                    </select>
                </div>

                <!-- Heure d'arrivée -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure d'arrivée</label>
                    <input type="time" name="heure_arrivee" 
                           value="{{ $attendance->heure_arrivee ? $attendance->heure_arrivee->format('H:i') : '' }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- Heure de départ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure de départ</label>
                    <input type="time" name="heure_depart" 
                           value="{{ $attendance->heure_depart ? $attendance->heure_depart->format('H:i') : '' }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- Motif d'absence -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'absence</label>
                    <textarea name="motif_absence" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2"
                              placeholder="Préciser le motif en cas d'absence ou de maladie...">{{ $attendance->motif_absence ?? '' }}</textarea>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('hr.attendance.index') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

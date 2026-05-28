@extends('layouts.app-with-sidebar')

@section('title', 'Demande de Congé')
@section('page-title', 'Demande de Congé')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('hr.leaves.my') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Nouvelle Demande de Congé</h2>
            <p class="text-gray-600 mt-1">Soumettez votre demande de congé pour validation</p>
        </div>
    </div>

    <!-- Solde de congés actuel -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Votre solde de congés - {{ date('Y') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Congés payés</p>
                <p class="text-2xl font-bold text-blue-600">{{ $leaveBalance->conges_payes_restants }} / {{ $leaveBalance->conges_payes_total }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($leaveBalance->conges_payes_restants / $leaveBalance->conges_payes_total) * 100 }}%"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Congés maladie</p>
                <p class="text-2xl font-bold text-green-600">{{ $leaveBalance->maladies_restants }} / {{ $leaveBalance->maladies_total }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($leaveBalance->maladies_restants / $leaveBalance->maladies_total) * 100 }}%"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Congés exceptionnels</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $leaveBalance->exceptionnels_restants }} / {{ $leaveBalance->exceptionnels_total }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($leaveBalance->exceptionnels_restants / $leaveBalance->exceptionnels_total) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-8 border border-white/20 shadow-lg">
            <form action="{{ route('hr.leaves.store') }}" method="POST">
                @csrf

                <!-- Type de congé -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de congé *</label>
                    <select name="type" required id="typeSelect" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        <option value="Congé payé" {{ old('type') == 'Congé payé' ? 'selected' : '' }}>Congé payé</option>
                        <option value="Congé maladie" {{ old('type') == 'Congé maladie' ? 'selected' : '' }}>Congé maladie</option>
                        <option value="Congé exceptionnel" {{ old('type') == 'Congé exceptionnel' ? 'selected' : '' }}>Congé exceptionnel</option>
                        <option value="Maternité" {{ old('type') == 'Maternité' ? 'selected' : '' }}>Maternité</option>
                        <option value="Paternité" {{ old('type') == 'Paternité' ? 'selected' : '' }}>Paternité</option>
                        <option value="Sans solde" {{ old('type') == 'Sans solde' ? 'selected' : '' }}>Sans solde</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                        <input type="date" name="date_debut" value="{{ old('date_debut') }}" required
                               min="{{ today()->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        @error('date_debut') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
                        <input type="date" name="date_fin" value="{{ old('date_fin') }}" required
                               min="{{ old('date_debut') ?? today()->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        @error('date_fin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Calcul automatique des jours -->
                <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Nombre de jours demandés:</span>
                        <span id="nombreJours" class="text-2xl font-bold text-yellow-600">0</span>
                    </div>
                    <div id="soldeWarning" class="mt-2 text-sm text-red-600 hidden">
                        ⚠️ Solde insuffisant pour ce type de congé
                    </div>
                </div>

                <!-- Motif -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif de la demande *</label>
                    <textarea name="motif" rows="4" required placeholder="Expliquez brièvement la raison de votre demande de congé..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500 focus:border-transparent">{{ old('motif') }}</textarea>
                    @error('motif') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Informations supplémentaires -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Informations importantes</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Votre demande sera soumise pour validation au Manager RH</li>
                        <li>• Vous recevrez une notification par email dès la décision</li>
                        <li>• Les week-ends et jours fériés sont inclus dans le décompte</li>
                        <li>• Pour les congés maladie, un justificatif peut être demandé</li>
                    </ul>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('hr.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit" id="submitBtn" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Historique récent -->
    <div class="max-w-2xl mx-auto mt-6">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Vos demandes récentes</h3>
            @php
                $recentLeaves = App\Models\Leave::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp
            @if ($recentLeaves->count() > 0)
                <div class="space-y-3">
                    @foreach ($recentLeaves as $leave)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $leave->type }}</p>
                                <p class="text-sm text-gray-600">{{ $leave->nombre_jours }} jours du {{ $leave->date_debut->format('d/m/Y') }} au {{ $leave->date_fin->format('d/m/Y') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full 
                                @if ($leave->statut == 'En attente') bg-yellow-100 text-yellow-800
                                @elseif ($leave->statut == 'Approuvé') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $leave->statut }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('hr.leaves.my') }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Voir toutes mes demandes →</a>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune demande précédente</p>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('typeSelect');
    const dateDebut = document.querySelector('input[name="date_debut"]');
    const dateFin = document.querySelector('input[name="date_fin"]');
    const nombreJoursSpan = document.getElementById('nombreJours');
    const soldeWarning = document.getElementById('soldeWarning');
    const submitBtn = document.getElementById('submitBtn');

    // Soldes disponibles
    const soldes = {
        'Congé payé': {{ $leaveBalance->conges_payes_restants }},
        'Congé maladie': {{ $leaveBalance->maladies_restants }},
        'Congé exceptionnel': {{ $leaveBalance->exceptionnels_restants }},
        'Maternité': 90, // 90 jours pour maternité
        'Paternité': 10, // 10 jours pour paternité
        'Sans solde': 999 // Illimité pour sans solde
    };

    function calculateDays() {
        if (!dateDebut.value || !dateFin.value) {
            nombreJoursSpan.textContent = '0';
            return;
        }

        const debut = new Date(dateDebut.value);
        const fin = new Date(dateFin.value);
        
        if (fin < debut) {
            nombreJoursSpan.textContent = '0';
            return;
        }

        // Calculer le nombre de jours inclus
        const diffTime = Math.abs(fin - debut);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        nombreJoursSpan.textContent = diffDays;

        // Vérifier le solde
        const type = typeSelect.value;
        if (type && soldes[type] !== undefined) {
            if (diffDays > soldes[type]) {
                soldeWarning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                soldeWarning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    // Mettre à jour la date minimale de fin quand la date de début change
    dateDebut.addEventListener('change', function() {
        dateFin.min = this.value;
        if (dateFin.value && dateFin.value < this.value) {
            dateFin.value = this.value;
        }
        calculateDays();
    });

    dateFin.addEventListener('change', calculateDays);
    typeSelect.addEventListener('change', calculateDays);

    // Calcul initial
    calculateDays();
});
</script>
@endsection

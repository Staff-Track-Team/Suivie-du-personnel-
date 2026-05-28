@extends('layouts.app-with-sidebar')

@section('title', 'Gestion des Présences')
@section('page-title', 'Gestion des Présences')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backToDashboard="true" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Gestion des Présences</h2>
            <p class="text-gray-600">{{ $attendances->count() }} pointage(s) enregistré(s)</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('hr.attendance.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Nouveau pointage
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-4 border border-white/20 shadow-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" id="searchInput" placeholder="Nom, matricule..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="Présent">Présent</option>
                    <option value="Absent">Absent</option>
                    <option value="Retard">Retard</option>
                    <option value="Congé">Congé</option>
                    <option value="Maladie">Maladie</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" id="dateStart" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" id="dateEnd" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>
    </div>

    <!-- Tableau des présences -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl border border-white/20 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heures</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($attendances as $attendance)
                        <tr class="hover:bg-gray-50 attendance-row" 
                            data-name="{{ $attendance->user->name }}" 
                            data-date="{{ $attendance->date->format('Y-m-d') }}" 
                            data-status="{{ $attendance->statut }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($attendance->user->profil)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $attendance->user->profil) }}" alt="{{ $attendance->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <span class="text-green-600 font-medium">{{ substr($attendance->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->user->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attendance->date->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $attendance->date->format('l') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($attendance->heure_arrivee)
                                    <div class="text-sm text-gray-900">{{ $attendance->heure_arrivee->format('H:i') }}</div>
                                    @if ($attendance->est_en_retard)
                                        <span class="text-xs text-red-600">+{{ $attendance->retard }} min</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($attendance->heure_depart)
                                    <div class="text-sm text-gray-900">{{ $attendance->heure_depart->format('H:i') }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $attendance->heures_travaillees }}h</div>
                                @if ($attendance->pause_debut && $attendance->pause_fin)
                                    <div class="text-xs text-gray-500">Pause: {{ $attendance->pause_debut->format('H:i') }}-{{ $attendance->pause_fin->format('H:i') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($attendance->statut == 'Présent') bg-green-100 text-green-800
                                    @elseif ($attendance->statut == 'Retard') bg-yellow-100 text-yellow-800
                                    @elseif ($attendance->statut == 'Absent') bg-red-100 text-red-800
                                    @elseif ($attendance->statut == 'Congé') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $attendance->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if ($attendance->motif_absence)
                                        <button onclick="showMotif('{{$attendance->motif_absence}}')" class="text-blue-600 hover:text-blue-900" title="Voir le motif">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('hr.attendance.edit', $attendance) }}" class="text-orange-600 hover:text-orange-900" title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <div x-data="{ deleteAttendance{{ $attendance->id }}Open: false }" class="inline">
                                        <button type="button" @click="deleteAttendance{{ $attendance->id }}Open = true" class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <x-confirm-modal 
                                            id="deleteAttendance{{ $attendance->id }}"
                                            title="Supprimer le pointage"
                                            message="Voulez-vous vraiment supprimer le pointage du {{ $attendance->date->format('d/m/Y') }} pour {{ $attendance->user->name }} ?"
                                            confirmText="Oui, supprimer"
                                        />
                                        
                                        <form id="form-deleteAttendance{{ $attendance->id }}" action="{{ route('hr.attendance.destroy', $attendance) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Aucun pointage trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($attendances->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Présences</p>
                    <p class="text-2xl font-bold text-green-600">{{ $attendances->where('statut', 'Présent')->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Retards</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $attendances->where('statut', 'Retard')->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Absences</p>
                    <p class="text-2xl font-bold text-red-600">{{ $attendances->where('statut', 'Absent')->count() }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total heures</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $attendances->sum('heures_travaillees') }}h</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher le motif -->
<div id="motifModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Motif d'absence</h3>
        <p id="motifText" class="text-gray-700 mb-4"></p>
        <div class="flex justify-end">
            <button onclick="hideMotifModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateStart = document.getElementById('dateStart');
    const dateEnd = document.getElementById('dateEnd');
    const attendanceRows = document.querySelectorAll('.attendance-row');

    function filterAttendances() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const startDate = dateStart.value;
        const endDate = dateEnd.value;

        attendanceRows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const date = row.dataset.date;
            const status = row.dataset.status.toLowerCase();

            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesDateRange = (!startDate || date >= startDate) && (!endDate || date <= endDate);

            if (matchesSearch && matchesStatus && matchesDateRange) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterAttendances);
    statusFilter.addEventListener('change', filterAttendances);
    dateStart.addEventListener('change', filterAttendances);
    dateEnd.addEventListener('change', filterAttendances);
});

function showMotif(motif) {
    document.getElementById('motifText').textContent = motif;
    document.getElementById('motifModal').classList.remove('hidden');
}

function hideMotifModal() {
    document.getElementById('motifModal').classList.add('hidden');
}
</script>
@endsection

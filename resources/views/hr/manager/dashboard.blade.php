@extends('layouts.app-with-sidebar')

@section('title', 'Tableau de bord Manager RH')
@section('page-title', 'Tableau de bord Manager RH')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Tableau de bord Manager RH</h1>
        <p class="text-gray-600">Gestion quotidienne des ressources humaines</p>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Employés</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_employes'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Congés en attente</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['conges_en_attente'] }}</p>
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
                    <p class="text-sm text-gray-600 mb-1">Présences cette semaine</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['presences_semaine'] }}</p>
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
                    <p class="text-sm text-gray-600 mb-1">Évaluations à faire</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['evaluations_a_faire'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Actions rapides</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('hr.employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-center transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Nouvel employé
                </a>
                <a href="{{ route('hr.attendance.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg text-center transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pointage
                </a>
                <a href="{{ route('hr.leaves.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-lg text-center transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Congés
                </a>
                <a href="{{ route('hr.employees.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg text-center transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Personnel
                </a>
            </div>
        </div>

        <!-- Horloge -->
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Heure actuelle</h2>
            <div class="text-center">
                <div id="clock" class="text-4xl font-bold text-blue-600 mb-2"></div>
                <div id="date" class="text-gray-600"></div>
            </div>
        </div>
    </div>

    <!-- Informations récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Demandes de congés à approuver -->
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Congés à approuver</h2>
            <div class="space-y-3">
                @forelse ($leavesToApprove as $leave)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div>
                            <p class="font-medium text-gray-800">{{ $leave->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $leave->type }} - {{ $leave->nombre_jours }} jours</p>
                            <p class="text-xs text-gray-500">Du {{ $leave->date_debut->format('d/m/Y') }} au {{ $leave->date_fin->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('hr.leaves.approve', $leave) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                    Approuver
                                </button>
                            </form>
                            <button onclick="showRejectModal({{ $leave->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                Refuser
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune demande en attente</p>
                @endforelse
            </div>
            @if ($leavesToApprove->count() > 0)
                <div class="mt-4 text-center">
                    <a href="{{ route('hr.leaves.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir toutes les demandes →</a>
                </div>
            @endif
        </div>

        <!-- Pointages récents -->
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pointages récents</h2>
            <div class="space-y-3">
                @forelse ($recentAttendances as $attendance)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $attendance->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $attendance->date->format('d/m/Y') }}</p>
                            @if ($attendance->heure_arrivee && $attendance->heure_depart)
                                <p class="text-xs text-gray-500">{{ $attendance->heure_arrivee->format('H:i') }} - {{ $attendance->heure_depart->format('H:i') }} ({{ $attendance->heures_travaillees }}h)</p>
                            @endif
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full 
                            @if ($attendance->statut == 'Présent') bg-green-100 text-green-800
                            @elseif ($attendance->statut == 'Retard') bg-yellow-100 text-yellow-800
                            @elseif ($attendance->statut == 'Absent') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $attendance->statut }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucun pointage récent</p>
                @endforelse
            </div>
            @if ($recentAttendances->count() > 0)
                <div class="mt-4 text-center">
                    <a href="{{ route('hr.attendance.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Voir tous les pointages →</a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour refuser un congé -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Refuser la demande de congé</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="leave_id" id="leave_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif du refus</label>
                <textarea name="commentaire_refus" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideRejectModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Refuser
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Horloge en temps réel
function updateClock() {
    const now = new Date();
    const clock = document.getElementById('clock');
    const date = document.getElementById('date');
    
    clock.textContent = now.toLocaleTimeString('fr-FR');
    date.textContent = now.toLocaleDateString('fr-FR', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

// Modal pour refuser un congé
function showRejectModal(leaveId) {
    document.getElementById('leave_id').value = leaveId;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectForm').action = '/hr/leaves/' + leaveId + '/reject';
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

updateClock();
setInterval(updateClock, 1000);
</script>
@endsection

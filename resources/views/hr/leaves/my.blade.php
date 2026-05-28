@extends('layouts.app-with-sidebar')

@section('title', 'Mes Demandes de Congé')
@section('page-title', 'Mes Demandes de Congé')

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
                <h2 class="text-2xl font-bold text-gray-900">Mes Demandes de Congé</h2>
            <p class="text-gray-600">{{ $leaves->count() }} demande(s) soumise(s)</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('hr.leaves.create') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvelle demande
            </a>
        </div>
    </div>

    <!-- Solde actuel -->
    @php
        $currentBalance = App\Models\LeaveBalance::getSoldeForUser(auth()->id());
    @endphp
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Mon solde de congés - {{ date('Y') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Congés payés</p>
                <p class="text-2xl font-bold text-blue-600">{{ $currentBalance->conges_payes_restants }} / {{ $currentBalance->conges_payes_total }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($currentBalance->conges_payes_restants / $currentBalance->conges_payes_total) * 100 }}%"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Congés maladie</p>
                <p class="text-2xl font-bold text-green-600">{{ $currentBalance->maladies_restants }} / {{ $currentBalance->maladies_total }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($currentBalance->maladies_restants / $currentBalance->maladies_total) * 100 }}%"></div>
                </div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Congés exceptionnels</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $currentBalance->exceptionnels_restants }} / {{ $currentBalance->exceptionnels_total }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($currentBalance->exceptionnels_restants / $currentBalance->exceptionnels_total) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-4 border border-white/20 shadow-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="En attente">En attente</option>
                    <option value="Approuvé">Approuvé</option>
                    <option value="Refusé">Refusé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select id="typeFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les types</option>
                    <option value="Congé payé">Congé payé</option>
                    <option value="Congé maladie">Congé maladie</option>
                    <option value="Congé exceptionnel">Congé exceptionnel</option>
                    <option value="Maternité">Maternité</option>
                    <option value="Paternité">Paternité</option>
                    <option value="Sans solde">Sans solde</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select id="periodFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Toutes les périodes</option>
                    <option value="current">Mois en cours</option>
                    <option value="next">Mois prochain</option>
                    <option value="past">Passé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tableau des demandes -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl border border-white/20 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandé le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($leaves as $leave)
                        <tr class="hover:bg-gray-50 leave-row" 
                            data-type="{{ $leave->type }}" 
                            data-status="{{ $leave->statut }}"
                            data-date="{{ $leave->date_debut->format('Y-m') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($leave->type == 'Congé payé') bg-blue-100 text-blue-800
                                    @elseif ($leave->type == 'Congé maladie') bg-green-100 text-green-800
                                    @elseif ($leave->type == 'Congé exceptionnel') bg-yellow-100 text-yellow-800
                                    @elseif ($leave->type == 'Maternité') bg-pink-100 text-pink-800
                                    @elseif ($leave->type == 'Paternité') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $leave->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $leave->date_debut->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">au {{ $leave->date_fin->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $leave->nombre_jours }} jours</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $leave->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $leave->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($leave->statut == 'En attente') bg-yellow-100 text-yellow-800
                                    @elseif ($leave->statut == 'Approuvé') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $leave->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showDetails({{ $leave->id }})" class="text-blue-600 hover:text-blue-900" title="Voir les détails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    
                                    @if ($leave->statut == 'En attente')
                                        <button onclick="showCancelModal({{ $leave->id }})" class="text-red-600 hover:text-red-900" title="Annuler">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucune demande de congé trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($leaves->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>

    <!-- Statistiques personnelles -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $leaves->where('statut', 'En attente')->count() }}</p>
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
                    <p class="text-sm text-gray-600 mb-1">Approuvés</p>
                    <p class="text-2xl font-bold text-green-600">{{ $leaves->where('statut', 'Approuvé')->count() }}</p>
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
                    <p class="text-sm text-gray-600 mb-1">Total jours pris</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $leaves->where('statut', 'Approuvé')->sum('nombre_jours') }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96 max-h-96 overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">Détails de la demande</h3>
        <div id="detailsContent"></div>
        <div class="flex justify-end mt-4">
            <button onclick="hideDetailsModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                Fermer
            </button>
        </div>
    </div>
</div>

<!-- Modal annulation -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Annuler la demande</h3>
        <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir annuler cette demande de congé ?</p>
        <form id="cancelForm" method="POST">
            @csrf
            <input type="hidden" name="leave_id" id="cancel_leave_id">
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideCancelModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Non
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Oui, annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Données des congés
const leavesData = @json($leaves->items());

document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const periodFilter = document.getElementById('periodFilter');
    const leaveRows = document.querySelectorAll('.leave-row');

    function filterLeaves() {
        const statusValue = statusFilter.value.toLowerCase();
        const typeValue = typeFilter.value.toLowerCase();
        const periodValue = periodFilter.value;

        leaveRows.forEach(row => {
            const type = row.dataset.type.toLowerCase();
            const status = row.dataset.status.toLowerCase();
            const date = row.dataset.date;
            
            const currentMonth = new Date().getMonth() + 1;
            const currentYear = new Date().getFullYear();
            const rowMonth = new Date(date + '-01').getMonth() + 1;
            const rowYear = new Date(date + '-01').getFullYear();

            let matchesPeriod = true;
            if (periodValue === 'current') {
                matchesPeriod = rowMonth === currentMonth && rowYear === currentYear;
            } else if (periodValue === 'next') {
                const nextMonth = currentMonth === 12 ? 1 : currentMonth + 1;
                const nextYear = currentMonth === 12 ? currentYear + 1 : currentYear;
                matchesPeriod = rowMonth === nextMonth && rowYear === nextYear;
            } else if (periodValue === 'past') {
                matchesPeriod = rowYear < currentYear || (rowYear === currentYear && rowMonth < currentMonth);
            }

            const matchesStatus = !statusValue || status === statusValue;
            const matchesType = !typeValue || type === typeValue;

            if (matchesStatus && matchesType && matchesPeriod) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    statusFilter.addEventListener('change', filterLeaves);
    typeFilter.addEventListener('change', filterLeaves);
    periodFilter.addEventListener('change', filterLeaves);
});

function showDetails(leaveId) {
    const leave = leavesData.find(l => l.id === leaveId);
    if (!leave) return;

    const content = `
        <div class="space-y-3">
            <div><strong>Type:</strong> ${leave.type}</div>
            <div><strong>Période:</strong> ${leave.date_debut} au ${leave.date_fin}</div>
            <div><strong>Durée:</strong> ${leave.nombre_jours} jours</div>
            <div><strong>Demandé le:</strong> ${leave.created_at}</div>
            <div><strong>Statut:</strong> ${leave.statut}</div>
            <div><strong>Motif:</strong> ${leave.motif}</div>
            ${leave.commentaire_refus ? `<div><strong>Commentaire de refus:</strong> ${leave.commentaire_refus}</div>` : ''}
            ${leave.date_decision ? `<div><strong>Décision le:</strong> ${leave.date_decision}</div>` : ''}
        </div>
    `;

    document.getElementById('detailsContent').innerHTML = content;
    document.getElementById('detailsModal').classList.remove('hidden');
}

function hideDetailsModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function showCancelModal(leaveId) {
    document.getElementById('cancel_leave_id').value = leaveId;
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelForm').action = '/hr/leaves/' + leaveId + '/cancel';
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelForm').reset();
}
</script>
@endsection

@extends('layouts.app-with-sidebar')

@section('title', 'Gestion du Personnel')
@section('page-title', 'Gestion du Personnel')

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
                <h2 class="text-2xl font-bold text-gray-900">Gestion du Personnel</h2>
                <p class="text-gray-600 mt-1">{{ $employees->count() }} employé(s) enregistré(s)</p>
            </div>
        <div class="flex space-x-3">
            <a href="{{ route('hr.employees.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvel employé
            </a>
            <a href="{{ route('hr.employees.export.excel') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exporter Excel
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-4 border border-white/20 shadow-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" id="searchInput" placeholder="Nom, email, matricule..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                <select id="roleFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les rôles</option>
                    <option value="employe">Employé</option>
                    <option value="manager_rh">Manager RH</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="Actif">Actif</option>
                    <option value="Inactif">Inactif</option>
                    <option value="Suspendu">Suspendu</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Département</label>
                <select id="departmentFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les départements</option>
                    @foreach($employees->pluck('employeeInfo.department')->unique()->filter() as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Tableau des employés -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl border border-white/20 shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poste</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-gray-50 employee-row" data-name="{{ $employee->name }}" data-email="{{ $employee->email }}" data-matricule="{{ $employee->matricule }}" data-role="{{ $employee->role }}" data-status="{{ $employee->status }}" data-department="{{ $employee->employeeInfo->department ?? '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($employee->profil)
                                            <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $employee->profil) }}" alt="{{ $employee->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-medium">{{ substr($employee->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $employee->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                                <div class="text-sm text-gray-500">{{ $employee->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $employee->employeeInfo->position ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $employee->employeeInfo->department ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($employee->role == 'admin') bg-purple-100 text-purple-800
                                    @elseif ($employee->role == 'manager_rh') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $employee->role_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($employee->status == 'Actif') bg-green-100 text-green-800
                                    @elseif ($employee->status == 'Inactif') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $employee->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('hr.employees.show', $employee) }}" class="text-indigo-600 hover:text-indigo-900" title="Voir les détails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('hr.employees.export.pdf', $employee) }}" class="text-green-600 hover:text-green-900" title="Exporter PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    @if(auth()->user()->isAdmin() || (auth()->user()->isManagerRH() && !$employee->isAdmin()))
                                    <div x-data="{ deleteEmployee{{ $employee->id }}Open: false }" class="inline">
                                        <button type="button" @click="deleteEmployee{{ $employee->id }}Open = true" class="text-red-600 hover:text-red-900" title="Supprimer l'employé">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <x-confirm-modal 
                                            id="deleteEmployee{{ $employee->id }}"
                                            title="Supprimer l'employé"
                                            message="Voulez-vous vraiment supprimer l'employé {{ $employee->name }} ? Cette action supprimera également toutes ses données rattachées."
                                            confirmText="Oui, supprimer"
                                        />
                                        
                                        <form id="form-deleteEmployee{{ $employee->id }}" action="{{ route('hr.employees.destroy', $employee) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun employé trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($employees->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Filtrage en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const departmentFilter = document.getElementById('departmentFilter');
    const employeeRows = document.querySelectorAll('.employee-row');

    function filterEmployees() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const departmentValue = departmentFilter.value.toLowerCase();

        employeeRows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const email = row.dataset.email.toLowerCase();
            const matricule = row.dataset.matricule.toLowerCase();
            const role = row.dataset.role.toLowerCase();
            const status = row.dataset.status.toLowerCase();
            const department = row.dataset.department.toLowerCase();

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm) || matricule.includes(searchTerm);
            const matchesRole = !roleValue || role === roleValue;
            const matchesStatus = !statusValue || status === statusValue;
            const matchesDepartment = !departmentValue || department.includes(departmentValue);

            if (matchesSearch && matchesRole && matchesStatus && matchesDepartment) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterEmployees);
    roleFilter.addEventListener('change', filterEmployees);
    statusFilter.addEventListener('change', filterEmployees);
    departmentFilter.addEventListener('change', filterEmployees);
});
</script>
@endsection

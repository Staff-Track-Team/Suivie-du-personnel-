@extends('layouts.app-with-sidebar')

@section('title', 'Détails de l\'employé')
@section('page-title', 'Détails de l\'employé')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backTo="{{ route('hr.employees.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-indigo-600">{{ substr($employee->name, 0, 2) }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $employee->name }}</h2>
                    <p class="text-gray-600">{{ $employee->matricule }} • {{ $employee->email }}</p>
                </div>
            </div>
            <div class="flex space-x-3 mt-4 sm:mt-0">
                <a href="{{ route('hr.employees.edit', $employee) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <form action="{{ route('hr.employees.destroy', $employee) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Matricule</label>
                        <p class="text-gray-900">{{ $employee->matricule }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $employee->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Rôle</label>
                        <p class="text-gray-900">{{ ucfirst($employee->role) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Statut</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $employee->status === 'Actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->status }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de naissance</label>
                        <p class="text-gray-900">{{ $employee->birthday ? \Carbon\Carbon::parse($employee->birthday)->format('d/m/Y') : 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Sexe</label>
                        <p class="text-gray-900">{{ $employee->gender ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Téléphone</label>
                        <p class="text-gray-900">{{ $employee->code_phone && $employee->phone ? $employee->code_phone . ' ' . $employee->phone : 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">N° CNI</label>
                        <p class="text-gray-900">{{ $employee->numero_cni ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">N° CNPS</label>
                        <p class="text-gray-900">{{ $employee->numero_cnps ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Situation matrimoniale</label>
                        <p class="text-gray-900">{{ $employee->situation_matrimoniale ?? 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nombre d'enfants</label>
                        <p class="text-gray-900">{{ $employee->nombre_enfants }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Niveau d'éducation</label>
                        <p class="text-gray-900">{{ $employee->niveau_education ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Compte bancaire</label>
                        <p class="text-gray-900">{{ $employee->compte_bancaire ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Contact d'urgence</label>
                        <p class="text-gray-900">{{ $employee->nom_urgence && $employee->contact_urgence ? $employee->nom_urgence . ' - ' . $employee->contact_urgence : 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Historique des présences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des présences (10 dernières)</h3>
                @if($employee->attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($employee->attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $attendance->status === 'present' ? 'Présent' : 'Absent' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aucun historique de présence disponible</p>
                @endif
            </div>

            <!-- Historique des congés -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Historique des congés (5 derniers)</h3>
                @if($employee->leaves->count() > 0)
                    <div class="space-y-3">
                        @foreach($employee->leaves as $leave)
                            <div class="border-l-4 {{ $leave->status === 'approved' ? 'border-green-500' : ($leave->status === 'rejected' ? 'border-red-500' : 'border-yellow-500') }} pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $leave->type }}</p>
                                        <p class="text-sm text-gray-600">
                                            Du {{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $leave->reason }}</p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $leave->status === 'approved' ? 'Approuvé' : ($leave->status === 'rejected' ? 'Rejeté' : 'En attente') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucune demande de congé disponible</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                @if($employee->documents->count() > 0)
                    <div class="space-y-2">
                        @foreach($employee->documents as $document)
                            <div class="flex items-center justify-between p-2 border rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-900">{{ $document->name }}</span>
                                </div>
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    Voir
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Aucun document disponible</p>
                @endif
            </div>

            <!-- Contrat -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contrat</h3>
                @if($employee->contract)
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Type de contrat</label>
                            <p class="text-gray-900">{{ $employee->contract->type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de début</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($employee->contract->start_date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date de fin</label>
                            <p class="text-gray-900">{{ $employee->contract->end_date ? \Carbon\Carbon::parse($employee->contract->end_date)->format('d/m/Y') : 'CDI' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Salaire</label>
                            <p class="text-gray-900">{{ number_format($employee->contract->salary, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">Aucun contrat disponible</p>
                @endif
            </div>

            <!-- Informations additionnelles -->
            @if($employee->employeeInfo)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations additionnelles</h3>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Département</label>
                            <p class="text-gray-900">{{ $employee->employeeInfo->department ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Poste</label>
                            <p class="text-gray-900">{{ $employee->employeeInfo->position ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Date d'embauche</label>
                            <p class="text-gray-900">{{ $employee->employeeInfo->hire_date ? \Carbon\Carbon::parse($employee->employeeInfo->hire_date)->format('d/m/Y') : 'Non renseignée' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Manager</label>
                            <p class="text-gray-900">{{ $employee->employeeInfo->manager_id ? User::find($employee->employeeInfo->manager_id)->name : 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Aucune information additionnelle disponible</p>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app-with-sidebar')

@section('title', 'Mon Tableau de bord')
@section('page-title', 'Mon Espace Personnel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mon Espace Personnel</h1>
        <p class="text-gray-600">Bienvenue {{ auth()->user()->name }}</p>
    </div>

    <!-- Solde de congés -->
    <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Mon solde de congés - {{ date('Y') }}</h2>
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

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('hr.leaves.create') }}" class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Demander un congé</h3>
                <p class="text-sm text-gray-600">Soumettre une nouvelle demande</p>
            </div>
        </a>

        <a href="{{ route('hr.leaves.my') }}" class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="text-center">
                <div class="bg-yellow-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Mes congés</h3>
                <p class="text-sm text-gray-600">Voir mes demandes</p>
            </div>
        </a>

        <a href="{{ route('profile.edit') }}" class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg hover:shadow-xl transition-shadow">
            <div class="text-center">
                <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Mon profil</h3>
                <p class="text-sm text-gray-600">Mettre à jour mes informations</p>
            </div>
        </a>
    </div>

    <!-- Informations récentes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pointages récents -->
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Mes pointages récents</h2>
            <div class="space-y-3">
                @forelse ($recentAttendances as $attendance)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $attendance->date->format('d/m/Y') }}</p>
                            @if ($attendance->heure_arrivee && $attendance->heure_depart)
                                <p class="text-sm text-gray-600">{{ $attendance->heure_arrivee->format('H:i') }} - {{ $attendance->heure_depart->format('H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $attendance->heures_travaillees }} heures travaillées</p>
                            @else
                                <p class="text-sm text-gray-600">{{ $attendance->statut }}</p>
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
        </div>

        <!-- Mes demandes de congés -->
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Mes demandes de congés</h2>
            <div class="space-y-3">
                @forelse ($myLeaves as $leave)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $leave->type }}</p>
                            <p class="text-sm text-gray-600">{{ $leave->nombre_jours }} jours</p>
                            <p class="text-xs text-gray-500">Du {{ $leave->date_debut->format('d/m/Y') }} au {{ $leave->date_fin->format('d/m/Y') }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full 
                            @if ($leave->statut == 'En attente') bg-yellow-100 text-yellow-800
                            @elseif ($leave->statut == 'Approuvé') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $leave->statut }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucune demande de congé</p>
                @endforelse
            </div>
            @if ($myLeaves->count() > 0)
                <div class="mt-4 text-center">
                    <a href="{{ route('hr.leaves.my') }}" class="text-green-600 hover:text-green-800 text-sm">Voir toutes mes demandes →</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulletins de paie récents -->
    @if ($myPayrolls->count() > 0)
        <div class="bg-white/80 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Mes bulletins de paie</h2>
            <div class="space-y-3">
                @foreach ($myPayrolls as $payroll)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $payroll->reference }}</p>
                            <p class="text-sm text-gray-600">Période {{ $payroll->periode }}</p>
                            <p class="text-sm font-semibold text-green-600">{{ number_format($payroll->salaire_net, 0, ',', ' ') }} FCFA</p>
                        </div>
                        @if ($payroll->bulletin_pdf)
                            <a href="{{ asset('storage/' . $payroll->bulletin_pdf) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                PDF
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app-with-sidebar')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <x-back-button backToDashboard="true" />
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Cover & Profile Image -->
            <div class="h-40 bg-gradient-to-br {{ auth()->user()->isAdmin() ? 'from-slate-900 via-blue-900 to-indigo-950' : 'from-teal-600 via-teal-700 to-emerald-800' }} relative">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
            </div>
            <div class="px-8 pb-8">
                <div class="relative flex flex-col md:flex-row justify-between items-center md:items-end -mt-16 mb-8 gap-4 text-center md:text-left">
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
                        <div class="w-32 h-32 rounded-3xl bg-white p-1.5 shadow-2xl relative">
                            @if(auth()->user()->profil)
                                <img src="{{ asset(auth()->user()->profil) }}" class="w-full h-full rounded-2xl object-cover">
                            @else
                                <div class="w-full h-full rounded-2xl {{ auth()->user()->isAdmin() ? 'bg-slate-900 text-white' : 'bg-teal-100 text-teal-700' }} flex items-center justify-center text-4xl font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-white p-1 shadow-lg">
                                <div class="w-full h-full rounded-lg {{ auth()->user()->status == 'Actif' ? 'bg-green-500' : 'bg-slate-400' }}"></div>
                            </div>
                        </div>
                        <div class="md:pb-2">
                            <h2 class="text-3xl font-black text-slate-800 tracking-tight">{{ auth()->user()->name }}</h2>
                            <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest rounded-md border border-slate-200">{{ auth()->user()->matricule }}</span>
                                <p class="text-indigo-600 font-bold text-sm bg-indigo-50 px-2 py-0.5 rounded-md">{{ auth()->user()->is_admin ? 'Administrateur Système' : ($user->employeeInfo->position ?? 'Employé CFP-CMD') }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-medium hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Modifier
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Informations Personnelles -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Informations Personnelles</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-400">Email</label>
                                <p class="text-slate-700 font-medium">{{ auth()->user()->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400">Matricule</label>
                                <p class="text-slate-700 font-medium font-mono">{{ auth()->user()->matricule }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400">Téléphone</label>
                                    <p class="text-slate-700 font-medium">{{ auth()->user()->phone ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400">Genre</label>
                                    <p class="text-slate-700 font-medium">{{ auth()->user()->gender ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400">Date de naissance</label>
                                <p class="text-slate-700 font-medium">{{ auth()->user()->birthday ? \Carbon\Carbon::parse(auth()->user()->birthday)->format('d/m/Y') : 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informations Professionnelles -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Informations Professionnelles</h3>
                        <div class="space-y-4">
                             <div>
                                <label class="block text-sm font-medium text-slate-400">Statut</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ auth()->user()->status }}
                                </span>
                            </div>
                            @if(!auth()->user()->isAdmin() && $user->employeeInfo)
                            <div>
                                <label class="block text-sm font-medium text-slate-400">Département</label>
                                <p class="text-slate-700 font-medium">{{ $user->employeeInfo->department ?? 'Non assigné' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-400">Date d'embauche</label>
                                <p class="text-slate-700 font-medium">{{ $user->employeeInfo->hired_at ? \Carbon\Carbon::parse($user->employeeInfo->hired_at)->format('d/m/Y') : 'Non renseignée' }}</p>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400">Ville</label>
                                <p class="text-slate-700 font-medium">{{ $user->employeeInfo->city ?? 'Non renseignée' }}</p>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-slate-400">Adresse</label>
                                <p class="text-slate-700 font-medium">{{ $user->employeeInfo->address ?? 'Non renseignée' }}</p>
                            </div>
                            @elseif(auth()->user()->isAdmin())
                            <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-500 text-sm">
                                Les administrateurs ont un accès complet au système.
                            </div>
                            @else
                            <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-500 text-sm">
                                Vos informations professionnelles seront ajoutées par le service RH.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

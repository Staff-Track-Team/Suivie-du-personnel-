@extends('layouts.app-with-sidebar')

@section('title', 'Modifier Employé')
@section('page-title', 'Modifier Employé')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ changePasswordModal: false }">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backUrl="{{ route('admin.employees.index') }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3">
                <a href="{{ route('admin.employees.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Modifier : {{ $employee->name }}</h1>
            </div>
            <p class="text-slate-500 ml-10">Mise à jour des informations du compte employé.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <div>
            <h3 class="font-bold text-red-800">Erreurs</h3>
            <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        @csrf
        @method('PUT')

        <!-- Colonne Principale (2/3) -->
        <div class="space-y-6 lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-blue-50/50 border-b border-blue-100 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Informations Personnelles</h2>
                </div>
                
                <div class="p-8">
                    <!-- Photo de profil -->
                    <div class="mb-8 flex flex-col items-center">
                        <div class="relative group" x-data="{ photoName: null, photoPreview: null }">
                            <input type="file" name="profil" id="photo" class="hidden"
                                   x-ref="photo"
                                   x-on:change="
                                           photoName = $refs.photo.files[0].name;
                                           const reader = new FileReader();
                                           reader.onload = (e) => { photoPreview = e.target.result; };
                                           reader.readAsDataURL($refs.photo.files[0]);
                                   ">
                            
                            <!-- Preview -->
                            <div class="w-32 h-32 rounded-full border-4 border-slate-100 shadow-inner bg-slate-50 flex items-center justify-center overflow-hidden relative cursor-pointer"
                                 x-on:click.prevent="$refs.photo.click()">
                                <template x-if="!photoPreview">
                                    @if($employee->profil)
                                        <img src="{{ asset($employee->profil) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 font-bold text-3xl">
                                            {{ substr($employee->name, 0, 1) }}
                                        </div>
                                    @endif
                                </template>
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /></svg>
                                </div>
                            </div>
                            <p class="text-xs text-center text-slate-400 mt-2">Modifier la photo</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date de naissance</label>
                            <input type="date" name="birthday" value="{{ old('birthday', $employee->birthday) }}" max="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Genre</label>
                            <select name="gender" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionner</option>
                                <option value="Masculin" {{ old('gender', $employee->gender) == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                <option value="Féminin" {{ old('gender', $employee->gender) == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Code Pays</label>
                            <select name="code_phone" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="+237" {{ old('code_phone', $employee->code_phone) == '+237' ? 'selected' : '' }}>+237 (Cameroun)</option>
                                <option value="+33" {{ old('code_phone', $employee->code_phone) == '+33' ? 'selected' : '' }}>+33 (France)</option>
                                <option value="+1" {{ old('code_phone', $employee->code_phone) == '+1' ? 'selected' : '' }}>+1 (USA/Canada)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations Professionnelles -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Informations Professionnelles</h2>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Département</label>
                            <input type="text" name="department" value="{{ old('department', $employee->employeeInfo->department ?? '') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Poste / Fonction</label>
                            <input type="text" name="position" value="{{ old('position', $employee->employeeInfo->position ?? '') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date d'embauche</label>
                            <input type="date" name="hired_at" value="{{ old('hired_at', $employee->employeeInfo->hired_at ?? '') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Localisation -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-emerald-50 border-b border-emerald-100 flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Localisation</h2>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ville</label>
                            <input type="text" name="city" value="{{ old('city', $employee->employeeInfo->city ?? '') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Adresse complète</label>
                            <input type="text" name="address" value="{{ old('address', $employee->employeeInfo->address ?? '') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changement Mot de Passe -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden p-6 flex flex-col items-center justify-center text-center gap-4">
                <div class="p-3 bg-slate-100 rounded-full text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Sécurité du compte</h3>
                    <p class="text-sm text-slate-500 mt-1">Modifiez le mot de passe de cet employé via une procédure sécurisée.</p>
                </div>
                <button type="button" @click="changePasswordModal = true" class="px-4 py-2 bg-slate-800 text-white rounded-xl hover:bg-slate-700 transition-colors text-sm font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Changer le mot de passe
                </button>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.employees.show', $employee->id) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Sauvegarder les modifications
                </button>
            </div>
        </div>

        <!-- Colonne Latérale -->
        <div class="space-y-6">
            <!-- Info Actuelles -->
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-all duration-300">
                <h3 class="font-bold text-slate-800 mb-4">Infos Système</h3>
                 <ul class="space-y-3 text-sm">
                    <li class="flex justify-between">
                        <span class="text-slate-500">Matricule</span>
                        <code class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $employee->matricule }}</code>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-slate-500">Créé le</span>
                        <span class="font-medium text-slate-800">{{ $employee->created_at->format('d/m/Y') }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-slate-500">Modifié le</span>
                        <span class="font-medium text-slate-800">{{ $employee->updated_at->format('d/m/Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </form>

    <!-- Modal Changement Mot de Passe -->
    <template x-teleport="body">
        <div x-show="changePasswordModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="changePasswordModal = false">
                    <div class="absolute inset-0 bg-slate-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-50" @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                    Changer le mot de passe
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">
                                        Veuillez saisir le mot de passe actuel pour confirmer l'identité, puis définissez le nouveau.
                                    </p>
                                    
                                    <form action="{{ route('admin.employees.update-password', $employee->id) }}" method="POST" id="passwordForm">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-1">Mot de passe actuel</label>
                                                <input type="password" name="current_password" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-1">Nouveau mot de passe</label>
                                                <input type="password" name="password" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-slate-700 mb-1">Confirmer nouveau</label>
                                                <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" onclick="document.getElementById('passwordForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Mettre à jour
                        </button>
                        <button type="button" @click="changePasswordModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

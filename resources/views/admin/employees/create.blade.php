@extends('layouts.app-with-sidebar')

@section('title', 'Nouvel Employé')
@section('page-title', 'Nouvel Employé')

@section('content')
<div class="max-w-4xl mx-auto">
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
                <h1 class="text-2xl font-bold text-slate-800">Nouvel employé</h1>
            </div>
            <p class="text-slate-500 ml-10">Création d'un nouveau compte employé.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
            <h3 class="font-bold text-red-800">Erreurs de validation</h3>
            <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        @csrf

        <!-- Colonne Principale (2/3) -->
        <div class="space-y-6 lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-blue-50/50 border-b border-blue-100 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
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
                            
                            <div class="w-32 h-32 rounded-full border-4 border-slate-100 shadow-inner bg-slate-50 flex items-center justify-center overflow-hidden relative cursor-pointer"
                                 x-on:click.prevent="$refs.photo.click()">
                                <template x-if="!photoPreview">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </template>
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-center text-slate-400 mt-2">Cliquez pour ajouter une photo</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Jean Dupont" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="jean.dupont@camtel.cm" class="w-full pl-10 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Date de naissance</label>
                            <input type="date" name="birthday" value="{{ old('birthday') }}" max="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Genre</label>
                            <select name="gender" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionner</option>
                                <option value="Masculin" {{ old('gender') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                <option value="Féminin" {{ old('gender') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Code Pays</label>
                            <select name="code_phone" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                <option value="+237" {{ old('code_phone') == '+237' ? 'selected' : '' }}>+237 (Cameroun)</option>
                                <option value="+33" {{ old('code_phone') == '+33' ? 'selected' : '' }}>+33 (France)</option>
                                <option value="+1" {{ old('code_phone') == '+1' ? 'selected' : '' }}>+1 (USA/Canada)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="690123456" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-purple-50/50 border-b border-purple-100 flex items-center gap-3">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Sécurité</h2>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Mot de passe <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                        <p class="text-xs text-slate-500 mt-1">Min. 8 caractères.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Confirmer <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.employees.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Créer l'employé
                </button>
            </div>
        </div>

        <!-- Colonne Latérale (1/3) -->
        <div class="space-y-6">
            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 hover:shadow-md transition-all duration-300">
                <h3 class="font-bold text-blue-800 flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Conseils
                </h3>
                <ul class="space-y-3 text-sm text-blue-700">
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>L'email servira d'identifiant de connexion.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Le mot de passe doit être fort par sécurité.</span>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>
@endsection

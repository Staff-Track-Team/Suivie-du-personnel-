@extends('layouts.app-with-sidebar')

@section('title', 'Modifier mon profil')
@section('page-title', 'Modifier mon profil')

@section('content')
    <div class="max-w-6xl mx-auto" x-data="{ passwordModal: false }">
        <!-- Back Button -->
        <div class="mb-6">
            <x-back-button backUrl="{{ route('profile') }}" />
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Colonne de Gauche : Informations (2/3) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white border-b border-slate-100 flex items-center gap-4">
                        <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tight">Informations Générales</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Identité et contact</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Photo de profil interactive -->
                        <div class="flex flex-col items-center">
                            <div class="relative group" x-data="{ photoName: null, photoPreview: null }">
                                <input type="file" name="profil" id="photo" class="hidden"
                                       x-ref="photo"
                                       x-on:change="
                                               photoName = $refs.photo.files[0].name;
                                               const reader = new FileReader();
                                               reader.onload = (e) => { photoPreview = e.target.result; };
                                               reader.readAsDataURL($refs.photo.files[0]);
                                       ">
                                
                                <div class="w-32 h-32 rounded-3xl bg-white p-1 shadow-xl border border-slate-100 overflow-hidden relative cursor-pointer"
                                     x-on:click.prevent="$refs.photo.click()">
                                    
                                    <template x-if="!photoPreview">
                                        @if(auth()->user()->profil)
                                            <img src="{{ asset(auth()->user()->profil) }}" class="w-full h-full rounded-2xl object-cover">
                                        @else
                                            <div class="w-full h-full rounded-2xl {{ auth()->user()->is_admin ? 'bg-slate-900 text-white' : 'bg-teal-100 text-teal-700' }} flex items-center justify-center text-4xl font-bold uppercase transition-all group-hover:scale-105">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </template>
                                    
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-full h-full rounded-2xl object-cover transition-all group-hover:scale-105">
                                    </template>

                                    <div class="absolute inset-0 bg-indigo-600/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-[2px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white scale-90 group-hover:scale-100 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-3 flex flex-col items-center">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Photo de profil</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nom complet</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3" required>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Adresse Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3" required>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Numéro de téléphone</label>
                                <div class="flex gap-2">
                                    <select name="code_phone" class="w-32 rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3 text-sm font-bold">
                                        <option value="+237" {{ old('code_phone', $user->code_phone) == '+237' ? 'selected' : '' }}>🇨🇲 +237</option>
                                        <option value="+33" {{ old('code_phone', $user->code_phone) == '+33' ? 'selected' : '' }}>🇫🇷 +33</option>
                                        <option value="+1" {{ old('code_phone', $user->code_phone) == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                        <option value="+221" {{ old('code_phone', $user->code_phone) == '+221' ? 'selected' : '' }}>🇸🇳 +221</option>
                                    </select>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="6xx xxx xxx" class="flex-1 rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3">
                                </div>
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Genre</label>
                                <select name="gender" id="gender" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3">
                                    <option value="">Sélectionner</option>
                                    <option value="Masculin" {{ old('gender', $user->gender) == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                    <option value="Féminin" {{ old('gender', $user->gender) == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                                </select>
                                @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                             <div>
                                <label for="birthday" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date de naissance</label>
                                <input type="date" name="birthday" id="birthday" value="{{ old('birthday', $user->birthday) }}" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3">
                                @error('birthday') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if(!$user->is_admin)
                        <div class="pt-8 border-t border-slate-100">
                             <h3 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                Localisation
                                <div class="h-px bg-indigo-100 flex-1"></div>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="city" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ville</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $user->employeeInfo->city ?? '') }}" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3">
                                </div>
                                <div>
                                    <label for="address" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Adresse de résidence</label>
                                    <input type="text" name="address" id="address" value="{{ old('address', $user->employeeInfo->address ?? '') }}" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-100">
                            <a href="{{ route('profile') }}" class="px-8 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all text-center">
                                Annuler
                            </a>
                            <button type="submit" class="px-8 py-3 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 text-center">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Colonne de Droite : Sécurité (1/3) -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden p-8 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 mb-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800">Sécurité du compte</h3>
                    <p class="text-sm text-slate-500 mt-2 mb-6">Protégez votre accès en mettant régulièrement à jour votre mot de passe.</p>
                    
                    <button type="button" @click="passwordModal = true" class="w-full py-3 px-6 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-all shadow-lg flex items-center justify-center gap-2 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Modifier le mot de passe
                    </button>
                    
                    @if($errors->has('current_password') || $errors->has('new_password'))
                        <div class="mt-4 p-3 bg-red-50 rounded-xl border border-red-100 text-red-600 text-xs font-bold uppercase tracking-tight">
                            Des erreurs sont survenues lors du changement de mot de passe.
                        </div>
                    @endif
                </div>

                <div class="bg-indigo-50 rounded-3xl p-8 border border-indigo-100">
                    <h4 class="font-bold text-indigo-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Conseils sécurité
                    </h4>
                    <ul class="space-y-3 text-xs text-indigo-700/80 leading-relaxed font-medium">
                        <li class="flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-1"></div>
                            Utilisez au moins 8 caractères.
                        </li>
                         <li class="flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-1"></div>
                            Mélangez lettres, chiffres et symboles.
                        </li>
                         <li class="flex items-start gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-1"></div>
                            Ne partagez jamais vos identifiants.
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- Modal de changement de mot de passe -->
        <template x-teleport="body">
            <div x-show="passwordModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="passwordModal = false">
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-75"></div>
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div 
                        x-show="passwordModal"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-50" 
                        @click.stop
                    >
                        <div class="p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Nouveau Mot de Passe</h3>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Mise à jour sécurisée</p>
                                </div>
                            </div>

                            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <!-- Preserving current data for validation -->
                                <input type="hidden" name="name" value="{{ $user->name }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <input type="hidden" name="phone" value="{{ $user->phone }}">
                                <input type="hidden" name="code_phone" value="{{ $user->code_phone }}">
                                <input type="hidden" name="gender" value="{{ $user->gender }}">
                                <input type="hidden" name="birthday" value="{{ $user->birthday }}">
                                @if($user->employeeInfo)
                                    <input type="hidden" name="city" value="{{ $user->employeeInfo->city }}">
                                    <input type="hidden" name="address" value="{{ $user->employeeInfo->address }}">
                                @endif

                                <div class="space-y-6">
                                    <div>
                                        <label for="current_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mot de passe actuel</label>
                                        <input type="password" name="current_password" id="current_password" class="w-full rounded-2xl border-slate-200 focus:border-red-500 focus:ring-red-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3" required>
                                        @error('current_password') <p class="text-red-500 text-[10px] font-bold mt-2 uppercase">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="h-px bg-slate-100"></div>

                                    <div>
                                        <label for="new_password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nouveau mot de passe</label>
                                        <input type="password" name="new_password" id="new_password" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3" required>
                                        @error('new_password') <p class="text-red-500 text-[10px] font-bold mt-2 uppercase">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="new_password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Confirmer le nouveau</label>
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-slate-50/50 focus:bg-white px-4 py-3" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-6">
                                    <button type="button" @click="passwordModal = false" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all text-sm">
                                        Annuler
                                    </button>
                                    <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 text-sm">
                                        Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>
@endsection

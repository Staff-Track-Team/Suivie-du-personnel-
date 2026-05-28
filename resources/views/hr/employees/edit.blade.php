@extends('layouts.app-with-sidebar')

@section('title', 'Modifier l\'employé')
@section('page-title', 'Modifier l\'employé')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <x-back-button backTo="{{ route('hr.employees.show', $employee) }}" />
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Modifier l'employé</h2>
        <p class="text-gray-600 mt-1">{{ $employee->name }} • {{ $employee->matricule }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('hr.employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations personnelles -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Informations personnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom & Prénoms <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700 mb-2">Matricule <span class="text-red-500">*</span></label>
                        <input type="text" id="matricule" name="matricule" value="{{ old('matricule', $employee->matricule) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('matricule') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle <span class="text-red-500">*</span></label>
                        <select id="role" name="role" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="employe" {{ old('role', $employee->role) == 'employe' ? 'selected' : '' }}>Employé</option>
                            <option value="manager_rh" {{ old('role', $employee->role) == 'manager_rh' ? 'selected' : '' }}>Manager RH</option>
                            <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Actif" {{ old('status', $employee->status) == 'Actif' ? 'selected' : '' }}>Actif</option>
                            <option value="Inactif" {{ old('status', $employee->status) == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                        <input type="date" id="birthday" name="birthday" value="{{ old('birthday', $employee->birthday) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('birthday') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                        <select id="gender" name="gender"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option value="Homme" {{ old('gender', $employee->gender) == 'Homme' ? 'selected' : '' }}>Homme</option>
                            <option value="Femme" {{ old('gender', $employee->gender) == 'Femme' ? 'selected' : '' }}>Femme</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="code_phone" class="block text-sm font-medium text-gray-700 mb-2">Indicatif téléphonique</label>
                        <input type="text" id="code_phone" name="code_phone" value="{{ old('code_phone', $employee->code_phone) }}" placeholder="+237"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('code_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="numero_cni" class="block text-sm font-medium text-gray-700 mb-2">Numéro CNI</label>
                        <input type="text" id="numero_cni" name="numero_cni" value="{{ old('numero_cni', $employee->numero_cni) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('numero_cni') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="numero_cnps" class="block text-sm font-medium text-gray-700 mb-2">Numéro CNPS</label>
                        <input type="text" id="numero_cnps" name="numero_cnps" value="{{ old('numero_cnps', $employee->numero_cnps) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('numero_cnps') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="situation_matrimoniale" class="block text-sm font-medium text-gray-700 mb-2">Situation matrimoniale</label>
                        <select id="situation_matrimoniale" name="situation_matrimoniale"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option value="Célibataire" {{ old('situation_matrimoniale', $employee->situation_matrimoniale) == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                            <option value="Marié(e)" {{ old('situation_matrimoniale', $employee->situation_matrimoniale) == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="Divorcé(e)" {{ old('situation_matrimoniale', $employee->situation_matrimoniale) == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="Veuf(ve)" {{ old('situation_matrimoniale', $employee->situation_matrimoniale) == 'Veuf(ve)' ? 'selected' : '' }}>Veuf(ve)</option>
                        </select>
                        @error('situation_matrimoniale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nombre_enfants" class="block text-sm font-medium text-gray-700 mb-2">Nombre d'enfants</label>
                        <input type="number" id="nombre_enfants" name="nombre_enfants" value="{{ old('nombre_enfants', $employee->nombre_enfants) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('nombre_enfants') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="niveau_education" class="block text-sm font-medium text-gray-700 mb-2">Niveau d'éducation</label>
                        <input type="text" id="niveau_education" name="niveau_education" value="{{ old('niveau_education', $employee->niveau_education) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('niveau_education') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="compte_bancaire" class="block text-sm font-medium text-gray-700 mb-2">Compte bancaire</label>
                        <input type="text" id="compte_bancaire" name="compte_bancaire" value="{{ old('compte_bancaire', $employee->compte_bancaire) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('compte_bancaire') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nom_urgence" class="block text-sm font-medium text-gray-700 mb-2">Nom du contact d'urgence</label>
                        <input type="text" id="nom_urgence" name="nom_urgence" value="{{ old('nom_urgence', $employee->nom_urgence) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('nom_urgence') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="contact_urgence" class="block text-sm font-medium text-gray-700 mb-2">Contact d'urgence</label>
                        <input type="tel" id="contact_urgence" name="contact_urgence" value="{{ old('contact_urgence', $employee->contact_urgence) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('contact_urgence') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('hr.employees.show', $employee) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors">
                    Annuler
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

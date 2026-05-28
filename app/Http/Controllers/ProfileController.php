<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur.
     */
    public function show()
    {
        $user = auth()->user()->load('employeeInfo');
        return view('profile.show', compact('user'));
    }

    /**
     * Affiche le formulaire d'édition du profil.
     */
    public function edit()
    {
        $user = auth()->user()->load('employeeInfo');
        return view('profile.edit', compact('user'));
    }

    /**
     * Met à jour les informations du profil.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'code_phone' => ['nullable', 'string', 'max:10'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Masculin,Féminin'],
            'profil' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
            // Champs spécifiques employé si nécessaire (adresse, etc.)
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
            'code_phone' => $validated['code_phone'] ?? $user->code_phone,
            'birthday' => $validated['birthday'] ?? $user->birthday,
            'gender' => $validated['gender'] ?? $user->gender,
        ]);

        if ($request->hasFile('profil')) {
            // Supprimer l'ancienne photo
            if ($user->profil && \Illuminate\Support\Facades\File::exists(public_path($user->profil))) {
                \Illuminate\Support\Facades\File::delete(public_path($user->profil));
            }
            
            $file = $request->file('profil');
            $filename = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profil'), $filename);
            $user->profil = 'profil/' . $filename;
        }

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        // Mise à jour des infos employé si elles existent
        if ($user->employeeInfo) {
            $user->employeeInfo->update([
                'address' => $validated['address'] ?? $user->employeeInfo->address,
                'city' => $validated['city'] ?? $user->employeeInfo->city,
            ]);
        }

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }
}

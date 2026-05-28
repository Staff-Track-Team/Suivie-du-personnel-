<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangedNotification;
use App\Mail\UserCreatedNotification;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // TARGET EMPLOYEES: is_admin = false
        $query = User::where('is_admin', false);

        // Filtre Recherche Globale
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtre Date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        // Filtre Statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre Genre
        if ($request->filled('gender')) {
            $targetGender = $request->gender == 'M' ? 'Masculin' : ($request->gender == 'F' ? 'Féminin' : $request->gender);
            $query->where('gender', $targetGender);
        }

        $employees = $query->latest()->paginate(10);

        // Statistiques
        $stats = [
            'total' => User::where('is_admin', false)->count(),
            'actifs' => User::where('is_admin', false)->where('status', 'Actif')->count(),
            'inactifs' => User::where('is_admin', false)->whereIn('status', ['Inactif', 'Suspendu'])->count(),
        ];

        return view('admin.employees.index', compact('employees', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Masculin,Féminin',
            'password' => 'required|min:8|confirmed',
            'profil' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $profilPath = null;
            if ($request->hasFile('profil')) {
                $file = $request->file('profil');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('profil'), $filename);
                $profilPath = 'profil/' . $filename;
            }

            $user = User::create([
                'matricule' => 'EMP-' . strtoupper(Str::random(6)),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
                'is_admin' => false,
                'status' => 'Actif',
                'profil' => $profilPath,
            ]);

            // Création automatique de l'objet info vide pour éviter les erreurs de relation nulle
            $user->employeeInfo()->create();

            // Envoyer l'email d'accueil
            Mail::to($user->email)->send(new UserCreatedNotification($user, $request->password));

            DB::commit();

            return redirect()->route('admin.employees.index')->with('success', 'Employé créé avec succès et informations envoyées par email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création ou de l\'envoi de l\'email : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employee = User::where('is_admin', false)->with('employeeInfo')->findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }

    public function tasks($id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);
        $tasks = \App\Models\Task::where('assigned_to', $id)
            ->with('project')
            ->get()
            ->groupBy('project_id');

        return view('admin.employees.tasks', compact('employee', 'tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($employee->id)],
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Masculin,Féminin',
            'profil' => 'nullable|image|max:2048',
        ]);

        $employee->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        if ($request->hasFile('profil')) {
            if ($employee->profil && File::exists(public_path($employee->profil))) {
                File::delete(public_path($employee->profil));
            }
            
            $file = $request->file('profil');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profil'), $filename);
            $employee->profil = 'profil/' . $filename;
        }

        $employee->save();

        // Mise à jour des informations étendues
        $employee->employeeInfo()->updateOrCreate(
            ['user_id' => $employee->id],
            [
                'department' => $request->department,
                'position' => $request->position,
                'hired_at' => $request->hired_at,
                'city' => $request->city,
                'address' => $request->address,
            ]
        );

        return redirect()->route('admin.employees.index')->with('success', 'Employé mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employé supprimé avec succès.');
    }

    // Actions spécifiques

    public function toggleStatus(Request $request, $id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);
        $employee->status = ($employee->status === 'Actif') ? 'Inactif' : 'Actif';
        $employee->save();

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    public function destroyGroup(Request $request)
    {
        $ids = json_decode($request->ids);
        User::whereIn('id', $ids)->where('is_admin', false)->delete();
        return back()->with('success', 'Employés supprimés avec succès.');
    }

    public function toggleStatusGroup(Request $request)
    {
        $ids = json_decode($request->ids);
        $targetStatus = $request->status ?? 'Actif';
        User::whereIn('id', $ids)->where('is_admin', false)->update(['status' => $targetStatus]);
        return back()->with('success', 'Statuts mis à jour avec succès.');
    }

    public function downloadPdf()
    {
        $employees = User::where('is_admin', false)->get();
        $pdf = Pdf::loadView('admin.employees.pdf', compact('employees'));
        return $pdf->download('employes_liste_' . date('d-m-Y') . '.pdf');
    }

    public function downloadExcel()
    {
        return Excel::download(new EmployeesExport, 'employes_liste_' . date('d-m-Y') . '.xlsx');
    }

    public function updatePassword(Request $request, $id)
    {
        $employee = User::where('is_admin', false)->findOrFail($id);

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        // Début de transaction pour garantir l'atomicité
        DB::beginTransaction();

        try {
            $employee->password = Hash::make($request->password);
            $employee->save();

            // Tentative d'envoi de l'email de notification
            Mail::to($employee->email)->send(new PasswordChangedNotification($employee));

            // Si tout est OK, on valide la transaction
            DB::commit();

            return back()->with('success', 'Mot de passe mis à jour et notification envoyée.');

        } catch (\Exception $e) {
            // ECHEC : On annule tout
            DB::rollBack();
            return back()->with('error', 'Erreur critique : Impossible d\'envoyer l\'email de notification. Le mot de passe N\'A PAS été modifié.');
        }
    }
}

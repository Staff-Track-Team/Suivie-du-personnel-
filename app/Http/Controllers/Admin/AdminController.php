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
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangedNotification;
use App\Mail\UserCreatedNotification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminsExport;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('is_admin', true);

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
        if ($request->filled('statut')) {
            // Mapping du statut de la vue (Activer/Desactiver) vers la DB (Actif/Inactif)
            $statusMap = ['Activer' => 'Actif', 'Desactiver' => 'Inactif'];
            $dbStatus = $statusMap[$request->statut] ?? $request->statut;
            $query->where('status', $dbStatus);
        }

        // Filtre Genre
        if ($request->filled('gender')) {
            $targetGender = $request->gender == 'M' ? 'Masculin' : ($request->gender == 'F' ? 'Féminin' : $request->gender);
            $query->where('gender', $targetGender);
        }

        $admins = $query->latest()->paginate(10);

        // Statistiques
        $stats = [
            'total' => User::where('is_admin', true)->count(),
            'actifs' => User::where('is_admin', true)->where('status', 'Actif')->count(),
            'inactifs' => User::where('is_admin', true)->whereIn('status', ['Inactif', 'Suspendu'])->count(),
        ];

        return view('admin.admins.index', compact('admins', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
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
                'matricule' => 'ADM-' . strtoupper(Str::random(6)),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
                'is_admin' => true,
                'status' => 'Actif',
                'profil' => $profilPath,
            ]);

            // Envoyer l'email d'accueil
            Mail::to($user->email)->send(new UserCreatedNotification($user, $request->password));

            DB::commit();

            return redirect()->route('admin.admins.index')->with('success', 'Administrateur créé avec succès et informations envoyées par email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de l\'administrateur ou de l\'envoi de l\'email : ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Masculin,Féminin',
            'password' => 'nullable|min:8|confirmed',
            'profil' => 'nullable|image|max:2048',
        ]);

        $admin->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        if ($request->hasFile('profil')) {
            // Supprimer l'ancienne photo si elle existe
            if ($admin->profil && File::exists(public_path($admin->profil))) {
                File::delete(public_path($admin->profil));
            }
            
            $file = $request->file('profil');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profil'), $filename);
            $admin->profil = 'profil/' . $filename;
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);
        
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Administrateur supprimé avec succès.');
    }

    // Actions spécifiques

    public function toggleStatus(Request $request, $id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);
        
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas changer votre propre statut.');
        }

        $admin->status = ($admin->status === 'Actif') ? 'Inactif' : 'Actif';
        $admin->save();

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    public function destroyGroup(Request $request)
    {
        $ids = json_decode($request->ids);
        
        // Filtrer pour ne pas supprimer soi-même
        $ids = array_filter($ids, function($id) {
            return $id != auth()->id();
        });

        User::whereIn('id', $ids)->where('is_admin', true)->delete();

        return back()->with('success', 'Administrateurs supprimés avec succès.');
    }

    public function toggleStatusGroup(Request $request)
    {
        $ids = json_decode($request->ids);
        $targetStatus = $request->statut === 'Activer' ? 'Actif' : 'Inactif';

        // Filtrer pour ne pas changer soi-même
        $ids = array_filter($ids, function($id) {
            return $id != auth()->id();
        });

        User::whereIn('id', $ids)->where('is_admin', true)->update(['status' => $targetStatus]);

        return back()->with('success', 'Statuts mis à jour avec succès.');
    }

    public function downloadPdf()
    {
        $admins = User::where('is_admin', true)->get();
        $pdf = Pdf::loadView('admin.admins.pdf', compact('admins'));
        return $pdf->download('admins_liste_' . date('d-m-Y') . '.pdf');
    }

    public function downloadExcel()
    {
        return Excel::download(new AdminsExport, 'admins_liste_' . date('d-m-Y') . '.xlsx');
    }

    public function updatePassword(Request $request, $id)
    {
        $admin = User::where('is_admin', true)->findOrFail($id);

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        // Début de transaction pour garantir l'atomicité
        DB::beginTransaction();

        try {
            $admin->password = Hash::make($request->password);
            $admin->save();

            // Tentative d'envoi de l'email de notification
            Mail::to($admin->email)->send(new PasswordChangedNotification($admin));

            // Si tout est OK, on valide la transaction
            DB::commit();

            return back()->with('success', 'Mot de passe mis à jour et notification envoyée.');

        } catch (\Exception $e) {
            // ECHEC : On annule la modification du mot de passe
            DB::rollBack();
            return back()->with('error', 'Erreur critique : Impossible d\'envoyer l\'email de notification. Le mot de passe N\'A PAS été modifié.');
        }
    }
}

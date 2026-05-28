<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contract;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\Payroll;
use App\Models\PerformanceEvaluation;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;

class HumanResourcesController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isManagerRH()) {
            return $this->managerRHDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_employes' => User::where('role', '!=', 'admin')->count(),
            'employes_actifs' => User::where('status', 'Actif')->where('role', '!=', 'admin')->count(),
            'contrats_actifs' => Contract::where('statut', 'Actif')->count(),
            'conges_en_attente' => Leave::where('statut', 'En attente')->count(),
            'presences_aujourd_hui' => Attendance::where('date', today())->where('statut', 'Présent')->count(),
            'evaluations_en_cours' => PerformanceEvaluation::where('statut', 'Brouillon')->count(),
        ];

        $recentLeaves = Leave::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $expiringContracts = Contract::where('date_fin', '<=', now()->addDays(30))
                                     ->where('date_fin', '>=', now())
                                     ->with('user')
                                     ->get();

        return view('hr.admin.dashboard', compact('stats', 'recentLeaves', 'expiringContracts'));
    }

    private function managerRHDashboard()
    {
        $stats = [
            'total_employes' => User::where('role', 'employe')->count(),
            'conges_en_attente' => Leave::where('statut', 'En attente')->count(),
            'presences_semaine' => Attendance::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'evaluations_a_faire' => PerformanceEvaluation::where('statut', 'Brouillon')->count(),
        ];

        $leavesToApprove = Leave::with('user')->where('statut', 'En attente')->get();
        $recentAttendances = Attendance::with('user')
                                       ->where('date', '>=', now()->subDays(7))
                                       ->orderBy('date', 'desc')
                                       ->take(10)
                                       ->get();

        return view('hr.manager.dashboard', compact('stats', 'leavesToApprove', 'recentAttendances'));
    }

    private function employeeDashboard()
    {
        $user = auth()->user();
        
        $leaveBalance = LeaveBalance::getSoldeForUser($user->id);
        $recentAttendances = Attendance::where('user_id', $user->id)
                                      ->where('date', '>=', now()->subDays(7))
                                      ->orderBy('date', 'desc')
                                      ->get();
        
        $myLeaves = Leave::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
        
        $myPayrolls = Payroll::where('user_id', $user->id)
                            ->orderBy('periode_debut', 'desc')
                            ->take(3)
                            ->get();

        return view('hr.employee.dashboard', compact('leaveBalance', 'recentAttendances', 'myLeaves', 'myPayrolls'));
    }

    // Gestion des employés
    public function employeesIndex()
    {
        $employees = User::where('role', '!=', 'admin')
                        ->with('employeeInfo', 'contract')
                        ->orderBy('name')
                        ->paginate(10);

        return view('hr.employees.index', compact('employees'));
    }

    public function employeesCreate()
    {
        return view('hr.employees.create');
    }

    public function employeesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'role' => 'required|in:manager_rh,employe',
            'department' => 'required|string',
            'position' => 'required|string',
            'hired_at' => 'required|date',
            'salaire_base' => 'required|numeric|min:0',
            'type_contrat' => 'required|in:CDI,CDD,Stage,Alternance,Freelance',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => 'Actif',
            'password' => Hash::make('password123'), // Mot de passe temporaire
            'matricule' => 'EMP' . str_pad(User::count() + 1, 4, '0', STR_PAD_LEFT),
        ]);

        // Créer les informations employé
        $user->employeeInfo()->create([
            'department' => $request->department,
            'position' => $request->position,
            'hired_at' => $request->hired_at,
        ]);

        // Créer le contrat
        $user->contract()->create([
            'reference' => 'CTR' . date('Y') . str_pad(Contract::count() + 1, 4, '0', STR_PAD_LEFT),
            'type' => $request->type_contrat,
            'date_debut' => $request->hired_at,
            'date_fin' => $request->type_contrat === 'CDI' ? null : $request->date_fin,
            'salaire_base' => $request->salaire_base,
            'poste' => $request->position,
            'statut' => 'Actif',
        ]);

        // Créer le solde de congés
        LeaveBalance::getSoldeForUser($user->id);

        return redirect()->route('hr.employees.index')
                        ->with('success', 'Employé créé avec succès. Un email sera envoyé avec les informations de connexion.');
    }

    public function employeesShow(User $employee)
    {
        $employee->load(['employeeInfo', 'contract', 'attendances' => function($query) {
            $query->orderBy('date', 'desc')->take(10);
        }, 'leaves' => function($query) {
            $query->orderBy('created_at', 'desc')->take(5);
        }, 'documents']);

        return view('hr.employees.show', compact('employee'));
    }

    public function employeesEdit(User $employee)
    {
        // Vérifier que l'utilisateur a le droit de modifier
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isManagerRH()) {
            abort(403);
        }

        // Empêcher la modification d'administrateurs par les managers RH
        if ($user->isManagerRH() && $employee->isAdmin()) {
            abort(403);
        }

        return view('hr.employees.edit', compact('employee'));
    }

    public function employeesUpdate(Request $request, User $employee)
    {
        // Vérifier que l'utilisateur a le droit de modifier
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isManagerRH()) {
            abort(403);
        }

        // Empêcher la modification d'administrateurs par les managers RH
        if ($user->isManagerRH() && $employee->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'matricule' => 'required|string|max:50|unique:users,matricule,' . $employee->id,
            'role' => 'required|in:employe,manager_rh,admin',
            'status' => 'required|in:Actif,Inactif',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:Homme,Femme',
            'code_phone' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'numero_cni' => 'nullable|string|max:50',
            'numero_cnps' => 'nullable|string|max:50',
            'situation_matrimoniale' => 'nullable|string|max:50',
            'nombre_enfants' => 'nullable|integer|min:0',
            'niveau_education' => 'nullable|string|max:100',
            'compte_bancaire' => 'nullable|string|max:50',
            'nom_urgence' => 'nullable|string|max:255',
            'contact_urgence' => 'nullable|string|max:50',
        ]);

        $employee->update($validated);

        return redirect()->route('hr.employees.show', $employee)
                        ->with('success', 'Employé mis à jour avec succès.');
    }

    public function employeesDestroy(User $employee)
    {
        // Vérifier que l'utilisateur a le droit de supprimer
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isManagerRH()) {
            abort(403);
        }

        // Empêcher la suppression d'administrateurs par les managers RH
        if ($user->isManagerRH() && $employee->isAdmin()) {
            abort(403);
        }

        $employee->delete();

        return redirect()->route('hr.employees.index')
                        ->with('success', 'Employé supprimé avec succès.');
    }

    // Gestion des présences
    public function attendanceIndex()
    {
        $attendances = Attendance::with('user')
                                ->orderBy('date', 'desc')
                                ->paginate(20);

        return view('hr.attendance.index', compact('attendances'));
    }

    public function attendanceCreate()
    {
        $employees = User::where('role', '!=', 'admin')->orderBy('name')->get();
        return view('hr.attendance.create', compact('employees'));
    }

    public function attendanceStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i',
            'statut' => 'required|in:Présent,Absent,Retard,Congé,Maladie',
            'motif_absence' => 'required_if:statut,Absent,Maladie',
        ]);

        // Vérifier si un pointage existe déjà pour cet utilisateur à cette date
        $existingAttendance = Attendance::where('user_id', $request->user_id)
                                      ->where('date', $request->date)
                                      ->first();

        if ($existingAttendance) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Un pointage existe déjà pour cet employé à cette date. Veuillez modifier le pointage existant au lieu d\'en créer un nouveau.');
        }

        $attendance = Attendance::create($request->all());
        
        // Calculer les heures travaillées
        if ($attendance->heure_arrivee && $attendance->heure_depart) {
            $attendance->heures_travaillees = $attendance->calculerHeuresTravaillees();
            $attendance->save();
        }

        return redirect()->route('hr.attendance.index')
                        ->with('success', 'Pointage enregistré avec succès.');
    }

    public function attendanceEdit(Attendance $attendance)
    {
        $employees = User::where('role', '!=', 'admin')->orderBy('name')->get();
        return view('hr.attendance.edit', compact('attendance', 'employees'));
    }

    public function attendanceUpdate(Request $request, Attendance $attendance)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i',
            'statut' => 'required|in:Présent,Absent,Retard,Congé,Maladie',
            'motif_absence' => 'required_if:statut,Absent,Maladie',
        ]);

        // Vérifier si un pointage existe déjà pour cet utilisateur à cette date (en excluant l'enregistrement actuel)
        $existingAttendance = Attendance::where('user_id', $request->user_id)
                                      ->where('date', $request->date)
                                      ->where('id', '!=', $attendance->id)
                                      ->first();

        if ($existingAttendance) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Un pointage existe déjà pour cet employé à cette date.');
        }

        $attendance->update($request->all());
        
        // Calculer les heures travaillées
        if ($attendance->heure_arrivee && $attendance->heure_depart) {
            $attendance->heures_travaillees = $attendance->calculerHeuresTravaillees();
            $attendance->save();
        }

        return redirect()->route('hr.attendance.index')
                        ->with('success', 'Pointage mis à jour avec succès.');
    }

    public function attendanceDestroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('hr.attendance.index')
                        ->with('success', 'Pointage supprimé avec succès.');
    }

    // Gestion des congés
    public function leavesIndex()
    {
        $leaves = Leave::with(['user', 'approver'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        return view('hr.leaves.index', compact('leaves'));
    }

    public function leavesCreate()
    {
        $user = auth()->user();
        $leaveBalance = LeaveBalance::getSoldeForUser($user->id);
        
        return view('hr.leaves.create', compact('leaveBalance'));
    }

    public function leavesStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Congé payé,Congé maladie,Congé exceptionnel,Maternité,Paternité,Sans solde',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        $nombreJours = (new \Carbon\Carbon($request->date_debut))->diffInDays(new \Carbon\Carbon($request->date_fin)) + 1;

        // Vérifier le solde disponible
        $leaveBalance = LeaveBalance::getSoldeForUser($user->id);
        
        switch ($request->type) {
            case 'Congé payé':
                if ($leaveBalance->conges_payes_restants < $nombreJours) {
                    return back()->withErrors('Solde de congés payés insuffisant.');
                }
                break;
            case 'Congé maladie':
                if ($leaveBalance->maladies_restants < $nombreJours) {
                    return back()->withErrors('Solde de congés maladie insuffisant.');
                }
                break;
            case 'Congé exceptionnel':
                if ($leaveBalance->exceptionnels_restants < $nombreJours) {
                    return back()->withErrors('Solde de congés exceptionnels insuffisant.');
                }
                break;
        }

        $leave = Leave::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'nombre_jours' => $nombreJours,
            'motif' => $request->motif,
            'statut' => 'En attente',
        ]);

        return redirect()->route('hr.leaves.my')
                        ->with('success', 'Demande de congé soumise avec succès.');
    }

    public function leavesApprove(Leave $leave)
    {
        $user = auth()->user();
        
        if (!$user->canManageRH()) {
            abort(403);
        }

        $leave->approuver($user->id);
        
        // Mettre à jour le solde de congés
        $leaveBalance = LeaveBalance::getSoldeForUser($leave->user_id);
        $leaveBalance->decrementerSolde($leave->type, $leave->nombre_jours);

        return back()->with('success', 'Congé approuvé avec succès.');
    }

    public function leavesReject(Request $request, Leave $leave)
    {
        $request->validate([
            'commentaire_refus' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        
        if (!$user->canManageRH()) {
            abort(403);
        }

        $leave->refuser($user->id, $request->commentaire_refus);

        return back()->with('success', 'Congé refusé.');
    }

    // Exportations
    public function exportEmployeesExcel()
    {
        return Excel::download(new EmployeesExport, 'employees_' . date('Y-m-d') . '.xlsx');
    }

    public function exportEmployeePDF(User $employee)
    {
        $employee->load(['employeeInfo', 'contract', 'documents']);
        
        $pdf = PDF::loadView('hr.employees.pdf', compact('employee'));
        
        return $pdf->download('fiche_employe_' . $employee->matricule . '.pdf');
    }

    public function myLeaves()
    {
        $user = auth()->user();
        $leaves = Leave::where('user_id', $user->id)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('hr.leaves.my', compact('leaves'));
    }
}

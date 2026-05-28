<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAudit;
use App\Models\User;
use App\Mail\TaskAssignedNotification;
use App\Mail\TaskUnassignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignee']);

        // Filtres
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->orderBy('project_id')->latest()->paginate(20); // increased limit to show better groups
        $projects = Project::select('id', 'name')->get(); // Pour le filtre

        // Statistiques globales (pour les cards)
        $stats = [
            'total' => Task::count(),
            'completed' => Task::where('status', 'Terminé')->count(),
            'urgent' => Task::where('priority', 'Urgente')->where('status', '!=', 'Terminé')->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'projects', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('status', '!=', 'Annulé')->get();
        return view('admin.tasks.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $task = Task::create([
                'project_id' => $request->project_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'priority' => $request->priority,
                'status' => 'À faire',
                'assigned_to' => $request->assigned_to,
            ]);

            // Audit
            TaskAudit::create([
                'task_id' => $task->id,
                'changed_by' => auth()->id(),
                'action' => 'created',
                'details' => json_encode(['title' => $task->title, 'assigned_to' => $task->assigned_to]),
                'old_status' => null,
                'new_status' => 'À faire',
            ]);

            // Notification Email
            if ($task->assigned_to) {
                try {
                    $user = User::find($task->assigned_to);
                    Mail::to($user->email)->send(new TaskAssignedNotification($task, $user));
                } catch (\Exception $e) {
                    // Si le mail ne part pas à la création, on tolère ou on fail ?
                    // "l'email d;information doit etre envoye... est le point le plus important" -> on fail la transaction si echec
                    DB::rollBack();
                    return back()->with('error', 'Erreur critique : Impossible d\'envoyer l\'email d\'assignation. La tâche n\'a pas été créée.');
                }
            }

            DB::commit();
            return back()->with('success', 'Tâche créée et assignée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de la tâche : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::with(['project', 'assignee', 'creator'])->findOrFail($id);
        $audits = TaskAudit::where('task_id', $task->id)->with('user')->latest()->get();
        
        return view('admin.tasks.show', compact('task', 'audits'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = Task::with('project')->findOrFail($id);
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $oldAssigneeId = $task->assigned_to;
        $oldStatus = $task->status; // Capture old status

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'status' => 'required|in:À faire,En cours,En attente,Terminé',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $task->fill([
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'priority' => $request->priority,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
            ]);

            // Detection changement assignation
            $newAssigneeId = $request->assigned_to;
            $assignmentChanged = ($oldAssigneeId != $newAssigneeId);
            $newStatus = $task->status;

            if ($task->isDirty()) {
                TaskAudit::create([
                    'task_id' => $task->id,
                    'changed_by' => auth()->id(),
                    'action' => 'updated',
                    'details' => json_encode($task->getDirty()),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]);
                $task->save();
            }

            // Notifications
            if ($assignmentChanged) {
                // 1. Notifier l'ancien (si existant)
                if ($oldAssigneeId) {
                    try {
                        $oldUser = User::find($oldAssigneeId);
                        Mail::to($oldUser->email)->send(new TaskUnassignedNotification($task, $oldUser));
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return back()->with('error', 'Erreur envoi mail de désassignation. Annulation.');
                    }
                }

                // 2. Notifier le nouveau (si existant)
                if ($newAssigneeId) {
                    try {
                        $newUser = User::find($newAssigneeId);
                        Mail::to($newUser->email)->send(new TaskAssignedNotification($task, $newUser));
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return back()->with('error', 'Erreur envoi mail d\'assignation. Annulation.');
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Tâche mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return back()->with('success', 'Tâche supprimée.');
    }
}

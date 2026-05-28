<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->assignedTasks()->with('project');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('project_id')->latest()->paginate(20);

        // Stats pour les cards
        $stats = [
            'total' => $user->assignedTasks()->count(),
            'pending' => $user->assignedTasks()->where('status', 'À faire')->count(),
            'in_progress' => $user->assignedTasks()->where('status', 'En cours')->count(),
            'completed' => $user->assignedTasks()->where('status', 'Terminé')->count(),
        ];

        return view('employee.tasks.index', compact('tasks', 'stats'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $task = $user->assignedTasks()->with(['project', 'project.creator'])->findOrFail($id);
        $audits = TaskAudit::where('task_id', $task->id)->with('user')->latest()->get();

        return view('employee.tasks.show', compact('task', 'audits'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $task = $user->assignedTasks()->findOrFail($id);
        
        if ($task->status === 'Terminé') {
            return back()->with('error', 'Vous ne pouvez plus modifier une tâche déjà terminée.');
        }

        $oldStatus = $task->status;

        $request->validate([
            'status' => 'required|in:À faire,En cours,En attente,Terminé',
        ]);

        DB::beginTransaction();

        try {
            if ($oldStatus !== $request->status) {
                $task->update(['status' => $request->status]);

                TaskAudit::create([
                    'task_id' => $task->id,
                    'changed_by' => $user->id,
                    'action' => 'status_change',
                    'details' => "Statut changé par l'employé",
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Statut de la tâche mis à jour !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour du statut.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskAudit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = TaskAudit::with(['task.project', 'user']);

        // Filtrer par Projet
        if ($request->filled('project_id')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        // Filtrer par Tâche (Search)
        if ($request->filled('search')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%");
            });
        }

        // Filtrer par Date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $audits = $query->latest()->paginate(20);
        $projects = Project::select('id', 'name')->get();

        return view('admin.audits.index', compact('audits', 'projects'));
    }
}

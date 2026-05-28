<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProjectController extends Controller
{
    public function downloadTasksPdf($id)
    {
        $project = Project::with(['tasks.assignee', 'creator'])->findOrFail($id);
        
        $taskStats = [
            'total' => $project->tasks->count(),
            'completed' => $project->tasks->where('status', 'Terminé')->count(),
        ];
        
        $completion = $taskStats['total'] > 0 ? round(($taskStats['completed'] / $taskStats['total']) * 100) : 0;

        $pdf = Pdf::loadView('admin.projects.tasks_pdf', compact('project', 'taskStats', 'completion'));
        
        return $pdf->download('projet_' . Str::slug($project->name) . '_tasks_' . date('d-m-Y') . '.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $projects = $query->with('creator')
            ->withCount('tasks')
            ->withCount(['tasks as completed_tasks_count' => function($q) {
                $q->where('status', 'Terminé');
            }])
            ->latest()->paginate(10);

        $stats = [
            'total' => Project::count(),
            'encours' => Project::where('status', 'En cours')->count(),
            'termines' => Project::where('status', 'Terminé')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:projects,code|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:En attente,En cours,Terminé,Suspendu,Annulé',
            'priority' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $code = $request->code ? strtoupper($request->code) : 'PRJ-' . date('Y') . '-' . strtoupper(Str::random(5));

        Project::create([
            'code' => $code,
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'priority' => $request->priority,
            'budget' => $request->budget,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Projet créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Project::with(['tasks.assignee', 'creator'])->findOrFail($id);
        
        $taskStats = [
            'total' => $project->tasks->count(),
            'completed' => $project->tasks->where('status', 'Terminé')->count(),
            'pending' => $project->tasks->where('status', 'À faire')->count(),
            'inprogress' => $project->tasks->where('status', 'En cours')->count(),
        ];

        // Calcul completion
        $completion = $taskStats['total'] > 0 ? round(($taskStats['completed'] / $taskStats['total']) * 100) : 0;

        return view('admin.projects.show', compact('project', 'taskStats', 'completion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:projects,code,'.$project->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:En attente,En cours,Terminé,Suspendu,Annulé',
            'priority' => 'required|in:Basse,Moyenne,Haute,Urgente',
             'budget' => 'nullable|numeric|min:0',
        ]);

        $project->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'priority' => $request->priority,
             'budget' => $request->budget,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Projet mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Projet supprimé.');
    }
}

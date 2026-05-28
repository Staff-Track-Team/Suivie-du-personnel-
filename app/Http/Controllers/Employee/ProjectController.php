<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // On récupère les projets où l'utilisateur a au moins une tâche assignée
        $projects = Project::whereHas('tasks', function($query) use ($userId) {
            $query->where('assigned_to', $userId);
        })
        ->withCount(['tasks' => function($query) use ($userId) {
            $query->where('assigned_to', $userId);
        }])
        ->get()
        ->map(function ($project) use ($userId) {
            $myTasks = $project->tasks()->where('assigned_to', $userId)->get();
            $totalMyTasks = $myTasks->count();
            $completedMyTasks = $myTasks->where('status', 'Terminé')->count();
            
            $project->my_progress = $totalMyTasks > 0 ? round(($completedMyTasks / $totalMyTasks) * 100) : 0;
            return $project;
        });

        return view('employee.projects.index', compact('projects'));
    }

    public function show($id)
    {
        $userId = auth()->id();
        
        $project = Project::whereHas('tasks', function($query) use ($userId) {
            $query->where('assigned_to', $userId);
        })
        ->with(['tasks' => function($query) use ($userId) {
            $query->where('assigned_to', $userId)->latest();
        }])
        ->findOrFail($id);

        $myTasks = $project->tasks;
        $totalMyTasks = $myTasks->count();
        $completedMyTasks = $myTasks->where('status', 'Terminé')->count();
        $project->my_progress = $totalMyTasks > 0 ? round(($completedMyTasks / $totalMyTasks) * 100) : 0;

        return view('employee.projects.show', compact('project', 'myTasks'));
    }
}

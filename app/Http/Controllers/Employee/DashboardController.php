<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;
        
        // 1. Statistiques Globales
        $totalTasks = $user->assignedTasks()->count();
        $pendingTasks = $user->assignedTasks()->where('status', 'À faire')->count();
        $inProgressTasks = $user->assignedTasks()->where('status', 'En cours')->count();
        $completedTasks = $user->assignedTasks()->where('status', 'Terminé')->count();
        
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // 2. Répartition (Charts)
        $tasksByStatus = $user->assignedTasks()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $tasksByPriority = $user->assignedTasks()
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        // 3. Activité 7 jours (Histogramme)
        $activityHistory = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = TaskAudit::whereHas('task', function($q) use ($userId) {
                $q->where('assigned_to', $userId);
            })
            ->whereDate('created_at', $date)
            ->count();
            
            $activityHistory[$date->translatedFormat('D d')] = $count;
        }

        // 4. Santé des Projets (Mes Projets)
        $projects = Project::whereHas('tasks', function($query) use ($userId) {
            $query->where('assigned_to', $userId);
        })
        ->get()
        ->map(function ($project) use ($userId) {
            $myTasks = $project->tasks()->where('assigned_to', $userId)->get();
            $totalMyTasks = $myTasks->count();
            $completedMyTasks = $myTasks->where('status', 'Terminé')->count();
            $project->my_progress = $totalMyTasks > 0 ? round(($completedMyTasks / $totalMyTasks) * 100) : 0;
            return $project;
        })->take(4);

        // 5. Timeline d'activité
        $recentAudits = TaskAudit::whereHas('task', function($q) use ($userId) {
            $q->where('assigned_to', $userId);
        })
        ->with(['task', 'user'])
        ->latest()
        ->take(6)
        ->get();

        return view('employee.dashboard', [
            'stats' => [
                'total_tasks' => $totalTasks,
                'pending' => $pendingTasks,
                'in_progress' => $inProgressTasks,
                'completed' => $completedTasks,
                'completion_rate' => $completionRate,
            ],
            'tasksByStatus' => $tasksByStatus,
            'tasksByPriority' => $tasksByPriority,
            'activityHistory' => $activityHistory,
            'recentProjects' => $projects,
            'recentAudits' => $recentAudits,
        ]);
    }
}

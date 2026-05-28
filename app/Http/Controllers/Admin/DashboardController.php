<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAudit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistiques Rapides (KPIs)
        $stats = [
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'total_employees' => User::where('is_admin', false)->count(),
            'completion_rate' => Task::count() > 0 ? round((Task::where('status', 'Terminé')->count() / Task::count()) * 100) : 0,
        ];

        // 2. Tâches par Statut (Pour Donut Chart)
        $tasksByStatus = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 3. Tâches par Priorité (Pour Pie Chart)
        $tasksByPriority = Task::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        // 4. Évolution de l'Activité (Histogramme - 7 derniers jours)
        $activityHistory = TaskAudit::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [Carbon::parse($item->date)->format('d M') => $item->count];
            })
            ->toArray();

        // 5. Projets Récents et Progress
        $recentProjects = Project::latest()
            ->take(5)
            ->get()
            ->map(function ($project) {
                $totalTasks = $project->tasks()->count();
                $completedTasks = $project->tasks()->where('status', 'Terminé')->count();
                $project->progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                return $project;
            });

        // 6. Activités Récentes (Timeline)
        $recentAudits = TaskAudit::with(['user', 'task'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'tasksByStatus', 
            'tasksByPriority', 
            'activityHistory', 
            'recentProjects',
            'recentAudits'
        ));
    }
}

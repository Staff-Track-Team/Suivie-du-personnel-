<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Projet - {{ $project->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #334155; line-height: 1.5; font-size: 12px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { color: #2563eb; font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .report-title { font-size: 18px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .project-info { background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
        .project-name { font-size: 20px; font-weight: bold; color: #1e293b; margin-bottom: 10px; }
        .info-grid { display: table; width: 100%; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-label { font-weight: bold; color: #94a3b8; font-size: 10px; text-transform: uppercase; }
        .info-value { font-size: 13px; color: #334155; margin-bottom: 10px; }
        .stats-box { margin-bottom: 30px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-block; width: 100%; box-sizing: border-box; }
        .stat-item { display: inline-block; width: 33%; text-align: center; }
        .stat-big { font-size: 18px; font-weight: bold; color: #2563eb; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f1f5f9; color: #475569; font-weight: bold; text-align: left; padding: 10px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .badge { padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: bold; display: inline-block; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-slate { background: #f1f5f9; color: #475569; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">CAMTEL</div>
        <div class="report-title">Rapport d'avancement des tâches</div>
    </div>

    <div class="project-info">
        <div class="project-name">{{ $project->name }} ({{ $project->code }})</div>
        <div class="info-grid">
            <div class="info-col">
                <div class="info-label">Statut</div>
                <div class="info-value">{{ $project->status }}</div>
                
                <div class="info-label">Période</div>
                <div class="info-value">{{ $project->start_date->format('d/m/Y') }} — {{ $project->end_date->format('d/m/Y') }}</div>
            </div>
            <div class="info-col">
                <div class="info-label">Priorité</div>
                <div class="info-value">{{ $project->priority }}</div>
                
                <div class="info-label">Chef de projet</div>
                <div class="info-value">{{ $project->creator->name }}</div>
            </div>
        </div>
    </div>

    <div class="stats-box">
        <div class="stat-item">
            <div class="info-label">Total Tâches</div>
            <div class="stat-big">{{ $taskStats['total'] }}</div>
        </div>
        <div class="stat-item">
            <div class="info-label">Terminées</div>
            <div class="stat-big">{{ $taskStats['completed'] }}</div>
        </div>
        <div class="stat-item">
            <div class="info-label">Progression</div>
            <div class="stat-big">{{ $completion }}%</div>
        </div>
    </div>

    <h3 style="margin-bottom: 10px; font-size: 14px; border-left: 4px solid #2563eb; padding-left: 10px;">Liste des tâches</h3>
    <table>
        <thead>
            <tr>
                <th>Titre de la tâche</th>
                <th>Assignée à</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Échéance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($project->tasks as $task)
            <tr>
                <td style="font-weight: 500;">{{ $task->title }}</td>
                <td>{{ $task->assignee->name ?? 'Non assignée' }}</td>
                <td>
                    <span class="badge {{ $task->priority == 'Urgente' ? 'badge-red' : ($task->priority == 'Haute' ? 'badge-orange' : 'badge-blue') }}">
                        {{ $task->priority }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $task->status == 'Terminé' ? 'badge-green' : ($task->status == 'En cours' ? 'badge-blue' : 'badge-slate') }}">
                        {{ $task->status }}
                    </span>
                </td>
                <td>{{ $task->end_date ? $task->end_date->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #94a3b8; padding: 30px;">Aucune tâche enregistrée pour ce projet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }} par le système de gestion de tâches Camtel.
    </div>
</body>
</html>

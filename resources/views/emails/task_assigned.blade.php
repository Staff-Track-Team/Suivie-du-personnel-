<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle tâche assignée - CFP-CMD</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #0056b3;">Nouvelle Tâche Assignée</h2>
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        
        <p>Une nouvelle tâche vous a été assignée sur le projet <strong>{{ $task->project->name }}</strong>.</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #0056b3;">
            <h3 style="margin-top: 0;">{{ $task->title }}</h3>
            <p>{{ Str::limit($task->description, 100) }}</p>
            <p><strong>Priorité :</strong> {{ $task->priority }}</p>
            <p><strong>Échéance :</strong> {{ $task->end_date ? $task->end_date->format('d/m/Y') : 'Non définie' }}</p>
        </div>

        <p>Connectez-vous pour voir les détails et commencer.</p>
        
        <a href="{{ route('login') }}" style="display: inline-block; padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">Accéder à la tâche</a>
    </div>
</body>
</html>

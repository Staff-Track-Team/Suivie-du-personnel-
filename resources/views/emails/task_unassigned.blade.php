<!DOCTYPE html>
<html>
<head>
    <title>Tâche retirée - CFP-CMD</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #64748b;">Tâche Retirée</h2>
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        
        <p>La tâche suivante vous a été retirée sur le projet <strong>{{ $task->project->name }}</strong> :</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #94a3b8;">
            <h3 style="margin-top: 0; color: #64748b;">{{ $task->title }}</h3>
        </div>

        <p>Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administrateur.</p>
    </div>
</body>
</html>

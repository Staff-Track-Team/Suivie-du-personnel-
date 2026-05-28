<!DOCTYPE html>
<html>
<head>
    <title>Changement de mot de passe - CFP-CMD</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="color: #0056b3;">CFP-CMD System</h1>
        </div>
        
        <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
        
        <p>Votre mot de passe a été modifié par un administrateur à l'instant.</p>
        
        <p>Si vous êtes à l'origine de cette demande ou si vous en avez été informé, vous pouvez ignorer ce message.</p>
        
        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Si vous n'êtes pas au courant de ce changement, veuillez contacter l'administrateur immédiatement.</strong>
        </div>

        <p>Cordialement,<br>L'équipe technique CFP-CMD</p>
    </div>
</body>
</html>

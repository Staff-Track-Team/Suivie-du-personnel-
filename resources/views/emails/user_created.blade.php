<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue chez CFP-CMD</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; background-color: #f8fafc; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #2563eb, #1e40af); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.025em; }
        .content { padding: 40px; }
        .welcome-text { font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 24px; }
        .info-card { background-color: #f1f5f9; border-radius: 12px; padding: 24px; margin-bottom: 32px; border: 1px solid #e2e8f0; }
        .info-item { margin-bottom: 16px; }
        .info-item:last-child { margin-bottom: 0; }
        .label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 4px; }
        .value { font-size: 15px; font-weight: 600; color: #334155; }
        .password-box { background: #ffffff; padding: 12px 16px; border-radius: 8px; border: 1px dashed #cbd5e1; font-family: monospace; font-size: 16px; color: #2563eb; margin-top: 8px; display: inline-block; }
        .button { display: inline-block; background-color: #2563eb; color: #ffffff !important; padding: 14px 32px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; margin-top: 8px; transition: all 0.2s; }
        .footer { padding: 24px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .warning { font-size: 12px; color: #ef4444; margin-top: 16px; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CFP-CMD PROJECT MANAGER</h1>
        </div>
        
        <div class="content">
            <p class="welcome-text">Bienvenue dans l'équipe, {{ $user->name }} !</p>
            
            <p>Votre compte a été créé avec succès par un administrateur. Vous pouvez désormais vous connecter à la plateforme pour gérer vos tâches et projets.</p>
            
            <div class="info-card">
                <div class="info-item">
                    <span class="label">Adresse Email</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Numéro Matricule</span>
                    <span class="value">{{ $user->matricule }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Mot de passe temporaire</span>
                    <div class="password-box">{{ $password }}</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="button">Se connecter maintenant</a>
            </div>

            <p class="warning">Par sécurité, il est fortement recommandé de modifier votre mot de passe dès votre première connexion.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CFP-CMD. Tous droits réservés.<br>
            Ceci est un message automatique, merci de ne pas y répondre.
        </div>
    </div>
</body>
</html>

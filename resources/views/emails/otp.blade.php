<x-mail::message>
# Code de Vérification

Bonjour,

Voici votre code de vérification sécurisé pour accéder à votre compte **Camtel**.

<x-mail::panel>
# {{ $otpCode }}
</x-mail::panel>

Ce code est valide pendant **10 minutes**.
Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>

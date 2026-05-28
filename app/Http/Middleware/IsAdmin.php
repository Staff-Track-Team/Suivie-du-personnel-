<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Si l'utilisateur n'est pas admin, redirection ou erreur 403
        // Pour une meilleure UX, on peut rediriger vers le dashboard employé si connecté
        if (auth()->check()) {
             return redirect()->route('employee.dashboard')->with('error', 'Accès non autorisé.');
        }

        return redirect()->route('login');
    }
}

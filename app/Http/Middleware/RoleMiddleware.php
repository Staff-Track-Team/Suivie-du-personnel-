<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Accès non autorisé');
                }
                break;
            case 'manager_rh':
                if (!$user->canManageRH()) {
                    abort(403, 'Accès non autorisé');
                }
                break;
            case 'employe':
                if (!$user->isEmploye() && !$user->canManageRH()) {
                    abort(403, 'Accès non autorisé');
                }
                break;
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $utilisateur = auth()->user();

        // Vérifier si l'utilisateur a le bon rôle
        if (!in_array($utilisateur->role, $roles)) {
            abort(403, 'Accès refusé. Vous n\'avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ?string $roles = null): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Non authentifié');
        }

        if (!$roles) {
            abort(403, 'Rôle requis manquant.');
        }

        $allowed = array_map('trim', explode('|', $roles));

        if (!in_array($user->role, $allowed, true)) {
            abort(403, 'Accès interdit pour votre rôle.');
        }

        return $next($request);
    }
}

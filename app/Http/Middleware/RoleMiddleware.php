<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Un administrateur a un droit de supervision global sur tous les espaces
        if ($user->isAdmin() || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->withErrors([
            'authorization' => 'Vous n’avez pas accès à cet espace.',
        ]);

        return $next($request);
    }
}

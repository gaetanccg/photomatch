<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!in_array($request->user()->role, $roles)) {
            // Redirect to the appropriate page based on user's actual role
            $redirectRoute = match ($request->user()->role) {
                'photographer' => 'photographer.dashboard',
                'client' => 'search.index',
                default => 'dashboard',
            };

            return redirect()->route($redirectRoute)
                ->with('error', 'Vous n\'avez pas acces a cette section.');
        }

        return $next($request);
    }
}

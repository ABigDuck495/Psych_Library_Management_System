<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Flatten roles if they come as comma-separated strings
        $allRoles = [];
        foreach ($roles as $role) {
            $allRoles = array_merge($allRoles, explode(',', $role));
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super-admin can do everything
        if ($user->role === 'super-admin') {
            return $next($request);
        }

        // If no specific roles were passed, allow authenticated users
        if (empty($allRoles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        if (in_array($user->role, $allRoles)) {
            return $next($request);
        }

        // If user doesn't have required role, show 403 error
        abort(403, 'Unauthorized action.');
    }
}

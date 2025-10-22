<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Accept either multiple middleware parameters or a single comma-separated string.
    // e.g. ->middleware('role:admin,super-admin') or ->middleware('role:admin','super-admin')
    $roleList = [];

    // If $roles is already an array-like via variadic parameters, normalize it.
    if (is_array($roles)) {
        $incoming = $roles;
    } else {
        // When Laravel passes middleware parameters, they come in as multiple args only if
        // the middleware signature uses a variadic parameter. To be robust, allow both.
        $incoming = func_get_args();
        // func_get_args returns [$request, $next, $roles, ...] so remove the first two
        if (count($incoming) > 2) {
            $incoming = array_slice($incoming, 2);
        } else {
            $incoming = [$roles];
        }
    }

    foreach ($incoming as $r) {
        if (is_null($r) || $r === '') {
            continue;
        }
        // if a single argument contains commas, split it
        if (is_string($r) && strpos($r, ',') !== false) {
            $parts = explode(',', $r);
            foreach ($parts as $p) {
                $roleList[] = strtolower(trim($p));
            }
        } else {
            $roleList[] = strtolower(trim((string) $r));
        }
    }

    // remove empty values and duplicates
    $roleList = array_values(array_filter(array_unique($roleList)));

    $user = Auth::user();
    $userRole = $user->role ?? null;

    if (is_null($userRole) || $userRole === '') {
        abort(403, 'Unauthorized access.');
    }

    $userRole = strtolower((string) $userRole);

    // If no valid roles were provided to the middleware, deny by default
    if (empty($roleList)) {
        abort(403, 'Unauthorized access.');
    }

    if (!in_array($userRole, $roleList, true)) {
        abort(403, 'Unauthorized access.');
    }

    return $next($request);
}
}

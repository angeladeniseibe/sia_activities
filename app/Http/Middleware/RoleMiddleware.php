<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next, $role): Response
{
    $user = auth()->user();

    // Check if user is logged in
    if (!$user) {
        abort(403, 'Unauthorized');
    }

    // ✅ ALLOW ADMIN TO ACCESS EVERYTHING
    if ($user->role === 'admin' || $user->role === $role) {
        return $next($request);
    }

    abort(403, 'Unauthorized');
}

}

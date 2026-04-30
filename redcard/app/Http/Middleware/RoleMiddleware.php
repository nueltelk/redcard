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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(403);
        }

        if ($role && $user->role !== $role) {
            abort(403);
        }

        return $next($request);
    }
}

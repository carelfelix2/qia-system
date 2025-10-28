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
        if (!$request->user()) {
            abort(403, 'Akses ditolak.');
        }

        $roles = explode('|', $role);
        foreach ($roles as $r) {
            if ($request->user()->hasRole($r)) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak.');
    }
}

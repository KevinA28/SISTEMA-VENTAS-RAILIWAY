<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->rol !== 'superadmin') {
            abort(403, 'Solo el superadministrador puede realizar esta acción.');
        }

        return $next($request);
    }
}

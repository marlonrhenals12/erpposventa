<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Superadmin siempre tiene acceso a cualquier módulo
        if ($request->user()->hasRole('superadmin')) {
            return $next($request);
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (! $request->user()->hasRole($roles)) {
            // Si es vendedor y no tiene permiso para este módulo, redirigir a ventas
            if ($request->user()->hasRole('vendedor')) {
                return redirect()->route('ventas.index');
            }

            abort(403, 'No tienes permisos para acceder a este módulo.');
        }

        return $next($request);
    }
}

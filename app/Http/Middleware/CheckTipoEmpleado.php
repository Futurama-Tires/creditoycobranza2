<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTipoEmpleado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tipo === 'empleado') {
            return $next($request);
        }

        // Si no es empleado, redirige o aborta
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}

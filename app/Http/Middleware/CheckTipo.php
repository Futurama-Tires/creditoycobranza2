<?php
// app/Http/Middleware/CheckTipo.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTipo
{
    public function handle(Request $request, Closure $next, string $tipo)
    {
        // If user is not authenticated
        if (!$request->user()) {
            return redirect('/');
        }

        $tipos = array_map('trim', explode('|', strtolower($tipo)));
        $userTipo = strtolower(trim($request->user()->tipo));

        if (!in_array($userTipo, $tipos)) {
            abort(403, 'No autorizado.');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class TrackDatabaseChanges
{
    public function handle($request, Closure $next)
    {
        // Solo para métodos que modifican la base de datos
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Registrar que hubo un cambio
            session()->flash('database_updated', true);
        }

        return $next($request);
    }
}
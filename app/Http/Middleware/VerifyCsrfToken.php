<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Array $except para excluir las rutas que no requieren CSRF token
        $except = [
            'api/v1/obtenerIdUsuario',
            'api/v1/comprobarUsuario'
        ];

        // Verificar si la ruta actual está en el array $except
        if (in_array($request->path(), $except)) {
            return $next($request); // Continuar con la solicitud sin verificar CSRF
        }

        // Verificar CSRF normalmente para otras rutas
        return $next($request);
    }
}

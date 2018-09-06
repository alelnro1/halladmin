<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Clase para validar que el usuario que esta logueado pueda acceder a la seccion deseada
 * Class CheckSection
 * @package App\Http\Middleware
 */
class AccesoRequerido
{
    /**
     * Handle an incoming request. El acceso requerido puede ser admin o super admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $acceso_requerido)
    {
        // Si pide super admin o admin y el usuario cumple, pasa, sino 404
        if ($acceso_requerido == "super admin") {
            if (!Auth::user()->esSuperAdmin()) {
                abort(404);
            }
        } else if ($acceso_requerido == "admin") {
            if (!Auth::user()->esAdmin())
                abort(404);
        }

        return $next($request);
    }
}
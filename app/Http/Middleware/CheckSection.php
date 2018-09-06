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
class CheckSection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $seccion_deseada)
    {
        // El super admin puede entrar a todos lados
        // Si es admin sigo para que se verifique en el siguiente Middleware
        if (Auth::user()->esSuperAdmin() || Auth::user()->esAdmin()) {
            return $next($request);
        }

        // Si tiene el modulo habilitado sigo
        if (Auth::user()->tieneModuloHabilitado($seccion_deseada)) {
            return $next($request);
        } else {
            Log::emergency(
                'Usuario ' . Auth::user()->id . ' tratando de entrar a una zona restringida ' . $seccion_deseada
            );

            abort(404);
        }

        /*dump(typeof($seccion_deseada));

        dump($seccion_deseada);
        $modulos_habilitados = session('MODULOS_HABILITADOS');

        // Busco dentro de los modulos habilitados del usuario actual, que tenga el modulo al que desea acceder
        foreach ($modulos_habilitados as $modulo_habilitado) {
            if (strtolower($modulo_habilitado->nombre) == $seccion_deseada) {
                return $next($request);
            }
        }*/

        Log::emergency(
            'Usuario ' . Auth::user()->id . ' tratando de entrar a una zona restringida ' . $seccion_deseada
        );

        abort(404);
    }
}
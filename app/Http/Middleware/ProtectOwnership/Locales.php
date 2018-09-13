<?php

namespace App\Http\Middleware\ProtectOwnership;

use Closure;
use Illuminate\Support\Facades\Auth;

class Locales
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
        // Obtengo el local a partir del ID de la URL
        $local = $request->route('local');

        // Obtengo los usuarios admin del local
        $usuarios_admin = $local->Usuarios;

        // Inicializo el flag como false para recorrer todos los usuarios
        $puede_ver = false;

        // Recorro todos los usuarios, si encuentro uno que es correcto => flag true y salgo
        foreach ($usuarios_admin as $usuario) {
            if (Auth::user()->id == $usuario->id) {
                $puede_ver = true;

                break;

                // TODO: Verificar que cuando quiere borrar/actualizar debe ser admin
            }
        }

        if ($puede_ver) {
            return $next($request);
        }

        abort(404);
    }
}

<?php

namespace App\Http\Middleware\ProtectOwnership;

use App\User;
use Closure;

class Empleados
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
        // Verificamos que el usuario pertenezca al local actual
        $usuarios_de_local = session('LOCAL_ACTUAL')->Usuarios;

        // Flag que dice si el usuario al que se quiere acceder pertenece al local
        $usuario_pertenece = false;

        // Obtengo el empleado a partir del ID de la URL
        if ($request->route()->parameter('usuario')) {
            $empleado_id = $request->route()->parameter('usuario');
        }

        if (isset($request->usuario->id)) {
            $empleado_id = $request->usuario->id;
        }

        // Recorremos los usuarios del local y si alguno matchea está ok
        foreach ($usuarios_de_local as $usuario_local) {
            if ($usuario_local->id == $empleado_id) {
                $usuario_pertenece = true;

                break;
            }
        }

        if ($usuario_pertenece) {
            return $next($request);
        }

        abort(404);
    }
}

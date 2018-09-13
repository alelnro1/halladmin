<?php

namespace App\Http\Middleware\ProtectOwnership;

use App\User;
use Closure;

class Usuarios
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

        // Recorremos los usuarios del local y si alguno matchea está ok
        foreach ($usuarios_de_local as $usuario_local) {
            if ($usuario_local->id == $request->usuario->id) {
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

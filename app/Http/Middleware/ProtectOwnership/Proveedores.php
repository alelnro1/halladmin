<?php

namespace App\Http\Middleware\ProtectOwnership;

use Closure;
use Illuminate\Support\Facades\Auth;

class Proveedores
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
        // Obtengo el proveedor a partir del ID de la URL
        $proveedor = $request->route('proveedor');

        // Si el proveedor no lo creó el usuario actual tirar error 404
        if (Auth::user()->id != $proveedor->usuario_id) {
            abort(404);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware\ProtectOwnership;

use Closure;

class Articulos
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
        $local = session('LOCAL_ACTUAL');

        $articulo = $request->articulo;

        if ($local->articuloPerteneceALocal($articulo)) {
            return $next($request);
        }

        abort(404);
    }
}

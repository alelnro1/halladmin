<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TieneAlgunLocal
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
        if (Auth::user()) {
            if (!Auth::user()->tieneAlgunLocal()) {
                return Redirect::to('locales')->with('user_no_tiene_locales', true)->send();
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SuccessfulLogin {

    public function start() {
        $this->cargarLocalesYAsignarElPrimero();

        if (Auth::user() && !Auth::user()->esSuperAdmin()) {
            // Seteo los módulos habilitados del menú para el usuario actual
            $modulos_habilitados = $this->getModulosHabilitados();

            // Le comparto a las vistas los módulos habilitados
            view()->share('MODULOS_HABILITADOS', $modulos_habilitados);
        }
    }

    /**
     * Se setea el nombre del local y si no hay ningun local asignado, se selecciona el primero
     * de la lista de locales del usuario.
     * Esto se usa al loguearse
     */
    protected function cargarLocalesYAsignarElPrimero()
    {
        // Busco al usuario logueado
        $user = Auth::user();

        // Si hay alguien logueado cargo los locales
        if ($user) {
            // Si no hay ningun local en la sesion
            if (session('LOCAL_ACTUAL') == null) {
                // Busco los locales
                $user->load('Locales');

                // Si existe al menos un local, seteo la sesion en true
                if (count($user->Locales) > 0) {
                    Auth::user()->setearSesionTieneLocal();
                }

                // Tomo el primer local
                $primer_local = $user->Locales->first();

                // Seteo el primer local como el seleccionado
                $this->setLocal($primer_local);
            }

            // Comparto con las vistas el local actual
            session(['LOCAL_NOMBRE' => $this->getLocalNombre()]);

            // Comparto con las vistas todos los locales del usuario actual
            session(['locales' => $user->locales]);

            View::share('LOCAL_NOMBRE', session('LOCAL_NOMBRE'));
            View::share('locales', session('locales'));
        }
    }

    /**
     * Creo una sesion con el objeto local
     * @param $local
     */
    public function setLocal($local)
    {
        // Creo la sesion con el local actual
        session(['LOCAL_ACTUAL' => $local]);
    }

    /**
     * Getter del nombre del local
     * @return null
     */
    public function getLocalNombre()
    {
        if (session('LOCAL_ACTUAL')) {
            return session('LOCAL_ACTUAL')->nombre;
        }

        return null;
    }
}
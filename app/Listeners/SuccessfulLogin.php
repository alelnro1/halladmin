<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SuccessfulLogin {

    public function start() {
        Auth::user()->cargarLocalesYAsignarElPrimero();

        if (Auth::user() && !Auth::user()->esSuperAdmin()) {
            // Seteo los módulos habilitados del menú para el usuario actual
            $modulos_habilitados = $this->getModulosHabilitados();

            // Le comparto a las vistas los módulos habilitados
            view()->share('MODULOS_HABILITADOS', $modulos_habilitados);
        }
    }

    /**
     * Getter de los módulos (menus) habilitados para el usuario actual
     * @return mixed
     */
    private function getModulosHabilitados()
    {
        // No se preguntó nunca a la base los módulos habilitados
        if (session('MODULOS_HABILITADOS') == null) {
            $this->setModulosHabilitados();
        }

        return session('MODULOS_HABILITADOS');
    }

    /**
     * Seteamos los módulos que el usuario puede ver acorde a lo que está en la base de datos.
     * Generamos una sesión con esos datos para no tener que hacer queries todo el tiempo
     */
    public function setModulosHabilitados()
    {
        // Busco en la base de datos
        $modulos = Auth::user()->Menus()->whereNull('padre_id')->get();

        // Cargo sólo los hijos que se hayan seleccionado
        $modulos->load([
            'MenusHijos' => function ($query) {
                $query->whereHas('Usuarios', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
        ]);

        // Si está habilitado el módulo cambios, debe estar habilitado el módulo ventas
        /*foreach ($modulos as $modulo) {
            if ($modulo->nombre == "ventas") {
                // Busco el id de nueva_venta
                $nuevo_cambio_menu = Menu::where('nombre', 'nuevo-cambio')->first();
                $cambios_menu = Menu::where('id', $nuevo_cambio_menu->padre_id)->first();

                // Vinculo al menu las ventas
                $modulos->push($cambios_menu);
            }
        }*/

        session(['MODULOS_HABILITADOS' => $modulos]);
    }
}
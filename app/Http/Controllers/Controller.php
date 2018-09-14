<?php

namespace App\Http\Controllers;

use App\Local;
use App\Menu;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Controller constructor.
     */
    public function __construct()
    {

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

    /**
     * Getter del ID del local
     * @return null
     */
    public function getLocalId()
    {
        if (session('LOCAL_ACTUAL')) {
            return session('LOCAL_ACTUAL')->id;
        }

        return null;
    }

    /**
     * Getter de los módulos (menus) habilitados para el usuario actual
     * @return mixed
     */
    public function getModulosHabilitados()
    {
        // No se preguntó nunca a la base los módulos habilitados
        if (session('MODULOS_HABILITADOS') == null) {
            $this->setModulosHabilitados();
        }

        return session('MODULOS_HABILITADOS');
    }
}

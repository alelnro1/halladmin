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
     * Creo una sesion con el objeto local
     * @param $local
     */
    public function setLocal($local)
    {
        // Creo la sesion con el local actual
        session(['LOCAL_ACTUAL' => $local]);
    }

    /**
     * Desde la vista se setea el local a gestionar
     * @param $local_id
     */
    public function setLocalDesdeVista($local_id)
    {
        // Verifico que el local corresponda al usuario actual
        if ( $this->localPerteneceAlUsuario($local_id)) {
            // Busco el local en la base
            $local = Local::findOrFail($local_id);
            
            // Sobreescribo la sesion
            $this->setLocal($local);
            echo json_encode(array('valid' => true));
        }
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
     * Verifico que el usuario contiene al local que quiero ir a ver
     * @param $local_id
     * @return bool
     */
    private function localPerteneceAlUsuario($local_id)
    {
        // Busco los locales del usuario
        $locales_usuario = Auth::user()->locales;

        $locales_usuario = $locales_usuario->filter(function ($local_usuario) use ($local_id){
            return $local_usuario->id == $local_id;
        });

        return $locales_usuario->count() > 0;
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

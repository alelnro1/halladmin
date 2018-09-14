<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     *
     * @param  $request
     * @param  $validator
     * @param  $local
     * @return mixed
     */
    protected function subirYGuardarArchivoSiHay($request, $modelo)
    {
        $extension = $request->archivo->getClientOriginalExtension();
        $nombre_archivo = rand(111111, 999999) . '_' . time() . "_." . $extension;

        $path = $request->archivo->storeAs('uploads/archivos', $nombre_archivo);

        $modelo->archivo = $path;
        $modelo->save();
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

            session(['LOCAL_NOMBRE' => $local->nombre]);

            return response()->json([
                'valid' => true
            ]);
        }
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
     * Creo una sesion con el objeto local
     * @param $local
     */
    public function setLocal($local)
    {
        // Creo la sesion con el local actual
        session(['LOCAL_ACTUAL' => $local]);
    }
}

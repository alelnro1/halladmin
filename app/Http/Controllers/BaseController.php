<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}

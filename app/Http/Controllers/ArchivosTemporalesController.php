<?php

namespace App\Http\Controllers;

use App\Articulo;
use App\MercaderiaTemporal;
use App\VentaTemporal;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArchivosTemporalesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Devuelvo un array con lo cantidad de articulos temporales
     * y con los articulos en un array aparte
     *
     * @result el array puede ser ventas o mercaderias temporales
     */
    public function getArrayTemporal($modelo)
    {
        // Array de return
        $resultado = array();

        // Busco la mercaderia temporal del usuario con el local
        $archivo_temporal =
            $modelo::where('local_id', $this->getLocalId())
                ->where('user_id', Auth::user()->id)
                ->select(['link'])
                ->first();

        Log::info('ArchivoTemporalesController. getArrayTemporal. El archivo que vamos a usar es: ' . $archivo_temporal);

        // Si hay algo temporal, lo tengo que cargar
        if ($archivo_temporal != null && $archivo_temporal->count() >= 1) {
            // Si el archivo no existe es porque se borro en el server => lo creo con ese nombre
            if (!Storage::exists($archivo_temporal->link)) {
                Storage::disk('local')->put($archivo_temporal->link, '');
            }

            // Obtengo el contenido del archivo
            $articulos_de_archivo = Storage::get($archivo_temporal->link);

            $articulos_de_archivo = explode("\n", $articulos_de_archivo);

            // Recorro las lineas del archivo
            foreach ($articulos_de_archivo as $key => $articulo){
                // Si el articulo no está vacío
                if ($articulo != "") {
                    // Un articulo se guarda por linea asi → codigo:1,descripcion:asd,precio:25
                    $articulo = explode(",", $articulo);

                    foreach ($articulo as $articulo_con_clave) {
                        $articulo_con_clave = explode(":", $articulo_con_clave);
                        $nombre_clave = $articulo_con_clave[0];
                        $valor = $articulo_con_clave[1];

                        $art_datos[$nombre_clave] = $valor;
                    }

                    array_push($resultado, $art_datos);
                }
            }
        }

        Log::critical('ArchivoTemporalesController. getArrayTemporal. Viene el resultado del archivo temporal');
        Log::critical(print_r($resultado, true));

        return $resultado;
    }

    /**
     * Si existe, se elimina el archivo y el registro de la base de datos de la mercaderia temporal
     */
    public function eliminarArchivosTemporales($modelo)
    {
        $user_id = Auth::user()->id;
        $local_id = $this->getLocalId();

        // Busco el link de la mercaderia temporal del usuario con el local
        $archivo_temporal =
            $modelo::where('local_id', $local_id)
                ->where('user_id', $user_id)
                ->select(['id', 'link'])
                ->first();

        // Borro el archivo si existe
        if ($archivo_temporal) {
            // Elimino la sesion
            if ($modelo == MercaderiaTemporal::class) {
                session(['link_mercaderia_temporal_local_de_user_' . $local_id . '_' . $user_id => null]);
            } else if ($modelo == VentaTemporal::class) {
                session(['link_venta_temporal_local_de_user_' . $local_id . '_' . $user_id => null]);
            }

            // Elimino el archivo
            //Storage::delete($archivo_temporal->link);
            Storage::disk('local')->put($archivo_temporal->link, '');

            // Elimino el registro
            //$archivo_temporal->delete();
        }
    }

    /**
     * Devuelve un link con el archivo temporal. Si no existe la sesión, lo crea
     *
     * @param  $local_id
     * @return mixed
     */
    public function getArchivoTemporalDeLocal($modelo)
    {
        $user_id = Auth::user()->id;
        $local_id = $this->getLocalId();
        $sesion = $this->getSesionConArchivoTemporal($modelo, $user_id, $local_id);
        $archivo = Storage::disk('local')->has($sesion);

        // Si no se creó una sesión con la mercadería temporal o es null, crearla
        if ($sesion == null) {
            $archivo_temporal =
                $modelo::where('local_id', $local_id)
                    ->where('user_id', $user_id)
                    ->select(['link'])
                    ->first();

            // Verifico si hay algun registro temporal creado del usuario actual
            if (!$archivo_temporal || $archivo_temporal->count() <= 0) {
                // Genero un archivo con el id del usuario, el id del local y un random
                $link = $user_id . "_" . $local_id . "_" . str_random(20);

                // Creo el archivo
                Storage::disk('local')->put($link, '');

                // Guardo el registro del archivo en la base
                $archivo_temporal =
                    $modelo::create(
                        [
                        'local_id' => $local_id,
                        'user_id' => $user_id,
                        'link' => $link
                        ]
                    );
            }

            $link = $archivo_temporal->link;

            // Creo la sesion segun el modelo
            if ($modelo == MercaderiaTemporal::class) {
                session(['link_mercaderia_temporal_local_de_user_' . $local_id . '_' . $user_id => $link]);
            } else if ($modelo == VentaTemporal::class) {
                session(['link_venta_temporal_local_de_user_' . $local_id . '_' . $user_id => $link]);
            }
        } else if ($sesion && $archivo) {
            // Existe la sesion y el archivo pero no el registro => se eliminó, y hay que crearlo
            $archivo_temporal =
                $modelo::where('local_id', $local_id)
                    ->where('user_id', $user_id)
                    ->select(['link'])
                    ->first();

            if (!$archivo_temporal) {
                // Guardo el registro del archivo en la base
                $archivo_temporal =
                    $modelo::create(
                        [
                            'local_id' => $local_id,
                            'user_id' => $user_id,
                            'link' => $sesion
                        ]
                    );
            }
        }

        return $this->getSesionConArchivoTemporal($modelo, $user_id, $local_id);
    }

    /**
     * Obtiene la sesion con el archivo temporal segun el modelo
     *
     * @param  $modelo
     * @param  $user_id
     * @param  $local_id
     * @return mixed|null
     */
    private function getSesionConArchivoTemporal($modelo, $user_id, $local_id)
    {
        // Inicializo la sesion
        $sesion = null;

        // Obtengo el nombre de la sesion segun el modelo
        if ($modelo == MercaderiaTemporal::class) {
            $sesion = session('link_mercaderia_temporal_local_de_user_' . $local_id . '_' . $user_id);
        } else if ($modelo == VentaTemporal::class) {
            $sesion = session('link_venta_temporal_local_de_user_' . $local_id . '_' . $user_id);
        }

        return $sesion;
    }
}

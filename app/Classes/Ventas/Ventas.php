<?php
/**
 * Created by PhpStorm.
 * User: alejandro.ponzo
 * Date: 14/9/2018
 * Time: 10:24
 */

namespace App\Classes\Ventas;

use App\Http\Controllers\ArchivosTemporalesController;
use App\Http\Controllers\CambiosController;
use App\Http\Controllers\Controller;
use App\Models\Mercaderia\Articulo;
use App\Models\Mercaderia\DatosArticulo;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaTemporal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Ventas extends ArchivosTemporalesController
{
    /**
     * Genero un numero de orden que no exista en la base de datos
     *
     * @return int
     */
    protected function generarNroOrden()
    {
        while (true) {
            // Genero un numero random
            $nro_orden = mt_rand();

            // Verifico que no exista en la base
            $venta = Venta::where('nro_orden', $nro_orden)->count();

            // Si no existe => devuelvo ese nro de orden
            if ($venta <= 0) {
                return $nro_orden;
            }
        }
    }

    /**
     * Se calcula el precio total de toda la venta
     *
     * @param $articulos
     */
    protected function calcularTotalVenta($articulos)
    {
        // Articulo a cambiar
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Inicializo el total
        $total = 0;

        // El total es el precio del articulo por la cantidad vendida
        foreach ($articulos as $articulo) {
            //$total = $total + ($articulo->DatosArticulo->precio * $articulo->cantidad_a_vender);
            if (isset($articulo->subtotal)) {
                $total = $total + $articulo->subtotal;
            } else {
                $total = $total + $articulo['subtotal'];
            }
        }

        // Si hay un articulo para cambiar, al total de la venta se le resta el saldo a favor
        if ($articulo_a_cambiar) {
            $total = $total - $articulo_a_cambiar->DatosArticulo->precio;
        }

        return $total;
    }

    /**
     * Se suman el total de articulo vendidos
     *
     * @param $articulos
     */
    protected function calcularCantidadArticulos($articulos)
    {
        // Inicializo el total
        $total = 0;

        // El total es el precio del articulo por la cantidad vendida
        foreach ($articulos as $articulo) {
            // TODO: Acoplar con la estructura del articulo. Se recibe como array y deberia ser objeto. Viene de aplicarDescuento
            /*if (isset($articulo->cantidad_a_vender)) {
                $total = $total + $articulo->cantidad_a_vender;
            } else {
                $total = $total + $articulo['cantidad'];
            }*/
            $total = $total + $articulo['cantidad_a_vender'];
        }

        return $total;
    }

    /**
     * Se eliminan los archivos y mercaderias temporales llamando al metodo y las sesiones
     */
    protected function eliminarArchivosTemporalesYSesiones()
    {
        // Elimino la mercaderia temporal y el registro de la base de datos
        $this->eliminarArchivosTemporales(VentaTemporal::class);

        // Se elimina la sesion que contiene al cliente al cual se le va a vender
        session(['VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID' => null]);

        // Se elimina la sesion que contiene todos los articulos temporales
        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS' => null]);

        // Se elimina la sesion que contiene el cambio
        session(['CAMBIO_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id => null]);
    }

    /**
     * Se obtienen los articulos temporales para armar el array de articulos definitivos a vender
     * @return array
     */
    public function armarArticulosParaVenderDeTemporales()
    {
        $archivos_temporales_controller = new ArchivosTemporalesController();

        // Inicializo la variable de articulos, donde estarán los articulos reales a vender
        $articulos = [];

        Log::info('armarArticulosParaVenderDeTemporales. Voy a buscar el Array Temporal');

        // Obtengo los artículos a vender
        $articulos_temporales = $archivos_temporales_controller->getArrayTemporal(VentaTemporal::class);

        Log::critical('armarArticulosParaVenderDeTemporales. Vienen los articulos temporales del array temporal');
        Log::critical(print_r($articulos_temporales, true));

        // Busco los articulos de verdad que vamos a vender
        foreach ($articulos_temporales as $articulo_temporal) {

            // Primero busco los datos del articulo y filtro por local por si están duplicados los códigos a través de distintos locales
            $datos_articulo = DatosArticulo::getArticuloConCodigo($articulo_temporal['codigo']);

            // Ahora sobre los datos del articulo, filtro por color y talle
            $articulo = Articulo::filtrarArticuloDeDatosId($datos_articulo, $articulo_temporal);

            // Guardo la cantidad de articulos a vender
            $articulo['cantidad_a_vender'] = $articulo_temporal['cantidad'];
            $articulo['subtotal'] = $articulo_temporal['subtotal'];
            $articulo['descuento'] = $articulo_temporal['descuento'];

            array_push($articulos, $articulo);
        }

        return $articulos;
    }
}
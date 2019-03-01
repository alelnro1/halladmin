<?php

namespace App\Classes\Ventas;


use App\Http\Controllers\ArchivosTemporalesController;
use App\Http\Controllers\CambiosController;
use App\Models\Cambio;
use App\Models\Cliente;
use App\Models\Mercaderia\Articulo;
use App\Models\Mercaderia\DatosArticulo;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaTemporal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConcretarVenta extends Ventas
{
    public function concretar()
    {
        // Obtengo los articulos
        $articulos = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS');

        // Obtengo el cliente si existe
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Obtengo el nro de orden
        $nro_orden = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_NRO_ORDEN');

        // Obtengo el medio de pago si es que hay alguno
        $medio_de_pago = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_MEDIO_DE_PAGO');

        // Si no hay articulos es porque hubo un problema de sesiones => regenero
        if (!$articulos) {
            $articulos = $this->armarArticulosParaVenderDeTemporales();
        }

        // Verifico que no se haya creado un nro_orden con el mismo numero mientras se realizaba la venta actual
        if (Venta::where('nro_orden', $nro_orden)->count() > 0) {
            $nro_orden = $this->generarNroOrden();
        }

        // Calculo el monto total de la venta
        $total_venta = $this->calcularTotalVenta($articulos);

        // Calculo la cantidad total de artículos a vender
        $cantidad_articulos_a_vender = $this->calcularCantidadArticulos($articulos);

        // Si hay un cliente actualizo el saldo
        if ($cliente_id) {
            // Busco el cliente
            $cliente = Cliente::findOrFail($cliente_id);
            $cliente->actualizaTuSaldo($total_venta);
        }

        // 1) Se registra la venta
        $venta =
            Venta::create([
                'cliente_id' => $cliente_id,
                'monto_total' => $total_venta,
                'local_id' => $this->getLocalId(),
                'user_id' => Auth::user()->id,
                'cantidad_articulos' => $cantidad_articulos_a_vender,
                'nro_orden' => $nro_orden,
                'medio_de_pago' => $medio_de_pago
            ]);

        // Si vengo de un cambio => lo creo
        $this->procesarCambio($venta);

        // 2) Para todos los articulos
        $this->actualizarStockYVincularVenta($articulos, $venta);

        // 3) Se eliminan los archivos temporales y las sesiones
        $this->eliminarArchivosTemporalesYSesiones();
    }

    private function actualizarStockYVincularVenta($articulos, $venta)
    {
        foreach ($articulos as $articulo) {
            $cantidad_a_vender = $articulo->cantidad_a_vender;

            // Actualizamos la cantidad remanente por ingreso por proveedor
            $articulo->actualizarCantidadesDeProveedores($cantidad_a_vender);

            // 2.1) Se resta el stock
            $articulo->cantidad = $articulo->cantidad - 1;

            // 2.2) Se guarda en la tabla articulo_venta con el precio y la cantidad
            $articulo->Ventas()->attach(
                $venta->id, [
                    'precio' => $articulo->getPrecioDefault(),
                    'cantidad' => $cantidad_a_vender,
                    'descuento' => $articulo->descuento,
                    'subtotal' => $articulo->subtotal,
                    'categoria_id' => $articulo->DatosArticulo->categoria_id,
                    'talle_id' => $articulo->talle_id,
                    'genero_id' => $articulo->genero_id
                ]
            );

            // Elimino el campo que contiene la cantidad a vender del articulo para poder guardar
            unset($articulo->cantidad_a_vender);

            // Elimino el campo que contiene el subtotal de cada articulo para poder guardar
            unset($articulo->subtotal);

            // Elimino el campo que contiene el descuento de cada articulo para poder guardar
            unset($articulo->descuento);

            $articulo->update([
                'cantidad' => $articulo->cantidad - $cantidad_a_vender
            ]);
        }
    }

    private function procesarCambio($venta)
    {
        // Si hay un cambio, se crea
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        if ($articulo_a_cambiar) {
            $cambio =
                Cambio::create([
                    'articulo_id' => $articulo_a_cambiar->id,
                    'local_id' => $this->getLocalId(),
                    'user_id' => Auth::user()->id,
                    'venta_id' => $venta->id
                ]);
        }
    }
}
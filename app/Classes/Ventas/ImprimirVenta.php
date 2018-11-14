<?php

namespace App\Classes\Ventas;


use App\Http\Controllers\CambiosController;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class ImprimirVenta extends Ventas
{
    public function imprimir()
    {
        // Obtengo al id del cliente al que se le venderá
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Busco al cliente
        $cliente = Cliente::where('id', $cliente_id)->first();

        // Busco los articulos que se vieron en la previsualizar
        $articulos = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS');

        // Calculo la cantidad total de artículos a vender
        $cantidad_articulos_a_vender = $this->calcularCantidadArticulos($articulos);

        // Calculo el total
        $total = $this->calcularTotalVenta($articulos);

        // Articulo a cambiar
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Obtengo el nro de orden
        $nro_orden = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_NRO_ORDEN');

        return view(
            'print.venta', [
                'articulos' => $articulos,
                'articulo_a_cambiar' => $articulo_a_cambiar,
                'cliente' => $cliente,
                'total' => $total,
                'nro_orden' => $nro_orden,
                'cantidad_articulos' => $cantidad_articulos_a_vender
            ]
        );
    }
}
<?php

namespace App\Classes\Ventas;


use App\Http\Controllers\AfipController;
use App\Http\Controllers\CambiosController;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrevisualizarVenta extends Ventas
{
    public function previsualizar()
    {
        // Obtengo al id del cliente al que se le venderá
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Busco al cliente
        $cliente = Cliente::where('id', $cliente_id)->first();

        Log::info('PREVISUALIZAR VENTA. Voy a buscar los articulos para vender.');

        // Inicializo la variable de articulos, donde estarán los articulos reales a vender
        $articulos = $this->armarArticulosParaVenderDeTemporales();

        Log::critical('PREVISUALIZAR VENTA. Vienen los articulos a vender.');
        Log::critical(print_r($articulos, true));

        // Creo el numero de orden
        $nro_orden = $this->generarNroOrden();
        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_NRO_ORDEN' => $nro_orden]);

        // Creo la sesion con los articulos para que sea de mas facil acceso
        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS' => $articulos]);

        // Calculo el total
        $total = $this->calcularTotalVenta($articulos);

        // Calculo la cantidad total de articulos a vender
        $cantidad_articulos = $this->calcularCantidadArticulos($articulos);

        // Articulo a cambiar
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Cargamos datos para AFIP
        $afip = new AfipController();
        $afip_tipos_comprobantes = $afip->getComprobantesDisponibles();
        $afip_tipos_documentos = $afip->getTiposDocumentos();
        $afip_tipos_conceptos = $afip->getTiposConceptos();

        return view('ventas.previsualizar', [
            'cliente' => $cliente,
            'articulos' => $articulos,
            'total' => $total,
            'nro_orden' => $nro_orden,
            'afip_tipos_comprobantes' => $afip_tipos_comprobantes,
            'afip_tipos_documentos' => $afip_tipos_documentos,
            'afip_tipos_conceptos' => $afip_tipos_conceptos,
            'cantidad_articulos' => $cantidad_articulos,
            'articulo_a_cambiar' => $articulo_a_cambiar
        ]);
    }
}
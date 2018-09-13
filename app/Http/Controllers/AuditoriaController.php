<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

use App\Http\Requests;

class AuditoriaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Guardo el nuevo movimiento para poder auditar
    public function nuevoMovimiento (
        $accion = null,
        $costo_anterior = null,
        $costo_nuevo = null,
        $precio_anterior = null,
        $precio_nuevo = null,
        $stock_anterior = null,
        $stock_nuevo = null,
        $cambio_id = null,
        $venta_id = null,
        $articulo_id = null,
        $proveedor_id = null,
        $user_id = null,
        $oferta_id = null
    ) {
        $movimiento =
            Auditoria::create([
                'unidad_funcional_id'   => $unidad_funcional_id,
                'accion'                => $accion,
                'saldo_anterior'        => $saldo_anterior,
                'saldo_nuevo'           => $saldo_nuevo,
                'interes_anterior'      => $interes_anterior,
                'interes_nuevo'         => $interes_nuevo,
                'estado_anterior_id'    => $estado_anterior_id,
                'estado_nuevo_id'       => $estado_nuevo_id,
                'comprobante_id'        => $comprobante_id,
                'liquidacion_id'        => $liquidacion_id
            ]);

        return $movimiento;
    }

    /**
     *
     * @param null $accion
     * @param null $saldo_anterior
     * @param null $saldo_nuevo
     * @param null $interes_anterior
     * @param null $interes_nuevo
     * @param null $comprobante_id
     * @param null $unidad_funcional_id
     * @param null $liquidacion_id
     * @return static
     */
    public function nuevoMovimiento (
        $accion=null,
        $saldo_anterior=null,
        $saldo_nuevo=null,
        $interes_anterior=null,
        $interes_nuevo=null,
        $estado_anterior_id=null,
        $estado_nuevo_id=null,
        $comprobante_id=null,
        $unidad_funcional_id=null,
        $liquidacion_id=null
    ) {
        $movimiento =
            Auditoria::create([
                'unidad_funcional_id'   => $unidad_funcional_id,
                'accion'                => $accion,
                'saldo_anterior'        => $saldo_anterior,
                'saldo_nuevo'           => $saldo_nuevo,
                'interes_anterior'      => $interes_anterior,
                'interes_nuevo'         => $interes_nuevo,
                'estado_anterior_id'    => $estado_anterior_id,
                'estado_nuevo_id'       => $estado_nuevo_id,
                'comprobante_id'        => $comprobante_id,
                'liquidacion_id'        => $liquidacion_id
            ]);

        return $movimiento;
    }
}

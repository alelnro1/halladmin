<?php
/**
 * Created by PhpStorm.
 * User: alejandro.ponzo
 * Date: 14/9/2018
 * Time: 10:49
 */

namespace App\Classes\Ventas;


use App\Models\Ventas\VentaCancelada;
use Illuminate\Support\Facades\Auth;

class CancelarVenta extends Ventas
{
    public function cancelar($request)
    {
        // Obtengo el cliente
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Creo el registro de la venta cancelada
        VentaCancelada::create([
            'user_id' => Auth::user()->id,
            'local_id' => $this->getLocalId(),
            'cliente_id' => $cliente_id,
            'motivo' => $request->motivo
        ]);

        // Se eliminan todos los datos temporales de la venta
        $this->eliminarArchivosTemporalesYSesiones();

        dump(session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID'));

        // Se elimina la sesion que contiene todos los articulos temporales
        dump(session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS'));

        // Se elimina la sesion que contiene el cambio
        dump(session('CAMBIO_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id));

        dd('aca');
    }
}
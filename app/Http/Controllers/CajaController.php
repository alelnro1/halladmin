<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CajaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listar()
    {
        $cajas = Caja::where('local_id', $this->getLocalId())->get();

        return view('cajas.listar')->with([
            'cajas' => $cajas
        ]);
    }

    public function abrirCaja()
    {
        // Obtengo el local actual
        $local_actual_id = session('LOCAL_ACTUAL')->id;

        $caja = Caja::getCajaLocalUserActual($local_actual_id, Auth::user()->id);

        if ($caja) {
            return redirect('caja/cerrar')->with(['caja_abierta' => true]);
        }

        // Busco si hay una caja abierta (porque se cerró la sesión, por ejemplo)
        /*$caja = Caja::find(Auth::user()->caja_id);

        // Si hay significa que ya se abrio y se perdió la sesión => hay que cerrar la caja
        if ($caja) {
            if ($caja->apertura != null && $caja->date_apertura != null) {
                return redirect('caja/cerrar')->with(['caja_abierta' => true]);
            }
        }*/

        return view('cajas.abrir');
    }

    public function procesarApertura(Request $request)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
                'monto' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return redirect('caja/abrir')->withErrors($validator)->withInput();
        }

        // Guardo que el usuario abrió la caja
        $caja = new Caja();
        $caja = $caja->registrarTransaccion($this->getLocalId(), Auth::user()->id, $request->monto, 'apertura');

        // Indico que el usuario abrió la caja en esta sesión
        Auth::user()->registrarCaja($caja->id);

        return redirect('/');
    }

    public function cerrarCaja()
    {
        if (!Auth::user()->abrioCaja()) {
            return redirect('caja/abrir')->with(['primero_apertura' => true]);
        }

        // Si el usuario actual ya abrio la caja, va a poder editarla, no se podrá crear una nueva
        return view('cajas.cerrar');
    }

    public function procesarCierre(Request $request)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
                'monto' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return redirect(route('caja.cerrar'))->withErrors($validator)->withInput();
        }

        // Guardo que el usuario abrió la caja
        $caja = new Caja();
        $caja = $caja->registrarTransaccion($this->getLocalId(), Auth::user()->id, $request->monto, 'cierre');

        // Deslogueo al usuario
        Auth::logout();

        return redirect('/');
    }

}

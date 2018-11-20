<?php

namespace App\Http\Controllers;

use App\Http\Requests\NegocioRequest;
use App\Models\Negocio;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    public function index()
    {
        $negocio_id = $this->getNegocioId();

        // Busco el negocio
        $negocio = Negocio::where('id', $negocio_id)->first();

        return view('negocio.index', [
            'negocio' => $negocio
        ]);
    }

    /**
     * Actualizamos la configuracion del negocio (datos frente a IVA)
     *
     * @param NegocioRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarConfig(NegocioRequest $request)
    {
        $negocio_id = $this->getNegocioId();

        // Busco el negocio
        $negocio = Negocio::where('id', $negocio_id)->first();

        // El CUIT viene con una mascara => La limpiamos
        $cuit = str_replace('-', '', $request->cuit);

        $negocio->cuit = $cuit;
        $negocio->condicion_iva = $request->condicion_iva;
        $negocio->save();

        return redirect(route('negocio'))->with(['actualizado' => true]);
    }
}

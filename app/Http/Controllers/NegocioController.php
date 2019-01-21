<?php

namespace App\Http\Controllers;

use App\Http\Requests\NegocioRequest;
use App\Models\Negocio;
use Illuminate\Http\Request;

class NegocioController extends Controller
{
    /**
     * Mostramos la info del negocio actual
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Traigo el negocio
        $negocio = $this->getNegocio();

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
        // Busco el negocio
        $negocio = $this->getNegocio();

        // El CUIT viene con una mascara => La limpiamos
        $cuit = str_replace('-', '', $request->cuit);

        $negocio->cuit = $cuit;
        $negocio->condicion_iva = $request->condicion_iva;
        $negocio->save();

        return redirect(route('negocio'))->with(['actualizado' => true]);
    }
}

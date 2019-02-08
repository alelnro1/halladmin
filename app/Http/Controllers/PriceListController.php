<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceList\AltaPriceListRequest;
use App\Models\Precios\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('price-list.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AltaPriceListRequest $request)
    {
        $negocio_id = false;

        if ($request->negocio) {
            $negocio_id = $this->getNegocioId();
        }

        PriceList::create([
            'negocio_id' => $negocio_id,
            'local_id' => $this->getLocalId(),

            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'vigencia_desde' => $request->vigencia_desde,
            'vigencia_hasta' => $request->vigencia_hasta,
            'dias' => $request->dias, // TODO: Esto va a tener que ser de un DatePicker para que seleccione los dias
            'activo' => $request->activo
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

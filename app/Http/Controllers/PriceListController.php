<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceList\AltaPriceListRequest;
use App\Models\Precios\PriceList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nexmo\Account\Price;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listas = $this->getLocal()->getPriceLists();
        return view('price-list.index');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AltaPriceListRequest $request)
    {
        //try {
        $negocio_id = $dias = $vigencia_desde = $vigencia_hasta = null;

        // Si mandan el negocio, le asignamos el id
        if ($request->negocio)
            $negocio_id = $this->getNegocioId();

        // Convertimos el array de dias a un string. Las claves son los dias
        if ($request->dias)
            $dias = implode(',', array_keys($request->dias));

        // Transformamos al formato fecha de base de datos
        if ($request->vigencia_desde)
            $vigencia_desde = Carbon::createFromFormat('d/m/Y H:i', $request->vigencia_desde);

        // Transformamos al formato fecha de base de datos
        if ($request->vigencia_hasta)
            $vigencia_hasta = Carbon::createFromFormat('d/m/Y H:i', $request->vigencia_hasta);

        /* Validamos si la lista debe/puede estar activa
         Debe, porque no activo el check de activa, y la fecha está en el rango de fechas
         Puede, porque sí marco el check de activa pero no está en el rango de fechas */
        if ($vigencia_desde && $vigencia_hasta) {
            $activo = Carbon::create()->between($vigencia_desde, $vigencia_hasta);
        }

        PriceList::create([
            'negocio_id' => $negocio_id,
            'local_id' => $this->getLocalId(),

            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'vigencia_desde' => $vigencia_desde->format('Y-m-d H:i'),
            'vigencia_hasta' => $vigencia_hasta->format('Y-m-d H:i'),
            'dias' => $dias,
            'activo' => $activo
        ]);

        return redirect(url('lista-precios'));
        /*} catch (\Exception $exception) {
            abort(500);
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price_list = PriceList::findOrFail($id);

        $articulos = $this->getNegocio()->getArticulos();

        return view('price-list.show', [
            'price_list' => $price_list
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

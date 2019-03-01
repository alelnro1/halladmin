<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Mercaderia\DatosArticulo;
use App\Models\Mercaderia\Genero;
use App\Models\Mercaderia\Talle;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mercaderia\Articulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticulosController extends Controller
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articulos = Articulo::getArticulos(true);

        return view('articulos.listar')->with('articulos', $articulos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articulos.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Articulo $articulo)
    {
        // Estos son los articulos que tienen el mismo codigo que el mostrado, serían
        // por ej: short rojo, verde, con talle 40, 41, etc
        $articulos_iguales = $articulo->DatosArticulo->Articulo;

        $proveedores = $articulo->Proveedores()->get();

        return view('articulos.show', [
            'articulo' => $articulo,
            'proveedores' => $proveedores,
            'articulos_iguales' => $articulos_iguales
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Articulo $articulo)
    {
        $articulo->load('DatosArticulo');

        $categorias = Categoria::all();
        $generos = Genero::all();
        $talles = Talle::all();

        return view('articulos.edit', [
            'articulo' => $articulo,
            'categorias' => $categorias,
            'generos' => $generos,
            'talles' => $talles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Articulo $articulo)
    {
        // Valido el input
        /*$validator = Validator::make(
            $request->all(), [
                'codigo' => 'required|max:100',
                'descripcion' => 'required|max:500',
                'categoria_id.*' => 'required|max:100',
                'precio' => 'required|numeric',
                'talle_id' => 'required',
                'color' => 'required|string',
                'genero_id' => 'required|max:100',
                'cantidad' => 'required'
            ]
        );

        if ($validator) {
            if ($validator->fails()) {
                return redirect('articulos/' . $id . '/edit')->withErrors($validator)->withInput();
            }
        }*/

        // Busco el articulo
        $articulo = $articulo->load([
            'DatosArticulo' => function ($query) {
                $query->select(['id', 'codigo']);
            }
        ]);


        // Primero actualizo los datos de articulos en común, si es que hay alguno distinto
        if ($this->hayDatosEnComunDistintos($articulo, $request)) {
            $datos_articulo = DatosArticulo::where('id', $articulo->DatosArticulo->id)->first();

            $datos_articulo->update([
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'categoria_id' => $request->categoria_id[0],
                'precio' => $request->precio,
                'genero_id' => $request->genero_id,
            ]);
        }

        if ($this->hayDatosExclusivosDistintos($articulo, $request)) {
            $articulo->update([
                'color' => $request->color,
                'talle_id' => $request->talle_id,
                'cantidad' => $request->cantidad
            ]);
        }

        return redirect(route('articulos'))->with('articulo_actualizado', 'Articulo actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);

        $articulo->delete();

        return redirect('/articulos/')->with('articulo_eliminado', 'Articulo con nombre ' . $articulo->nombre . ' eliminado');
    }

    private function hayDatosEnComunDistintos($articulo, $request)
    {
        $datos_comunes = $articulo->DatosArticulo;

        if ($datos_comunes->codigo != $request->codigo
            || $datos_comunes->descripcion != $request->descripcion
            || $datos_comunes->categoria_id != $request->categoria_id
            || $datos_comunes->precio != $request->precio
        ) {
            return true;
        }

        return false;
    }

    private function hayDatosExclusivosDistintos($articulo, $request)
    {
        $datos_exclusivos = $articulo->DatosArticulo;

        if ($datos_exclusivos->talle_id != $request->talle_id
            || $datos_exclusivos->color != $request->color
            || $datos_exclusivos->genero_id != $request->genero_id
            || $datos_exclusivos->cantidad != $request->cantidad
        ) {
            return true;
        }

        return false;
    }
}

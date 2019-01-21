<?php

namespace App\Http\Controllers;

use App\Http\Requests\Talles\AltaTalleRequest;
use App\Http\Requests\Talles\EditarTalleRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Mercaderia\Talle;
use Illuminate\Support\Facades\Validator;

class TallesController extends Controller
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
        $talles = Talle::all();

        return view('talles.listar', [
            'talles' => $talles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();

        return view('talles.create', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AltaTalleRequest $request)
    {
        // Valido el input
        /*$validator = Validator::make($request->all(), [
            'nombre'        => 'required|max:100',
            'categoria_id'  => 'required'
        ]);
        
        if ($validator->fails())
            return redirect('talles/create')->withErrors($validator)->withInput();*/
        
        // Creo el talle
        Talle::create($request->all());

        return redirect('/talles/')->with('talle_creado', 'Talle con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $talle = Talle::findOrFail($id);

        $talle->load(['tipo' => function($query){
            $query->select(['id', 'nombre']);
        }]);

        $categorias = Categoria::all();

        return view('talles.edit', [
            'talle' => $talle,
            'categorias' => $categorias
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarTalleRequest $request, $id)
    {
        /*// Valido el input
        $validator = Validator::make($request->all(), [
            'nombre'        => 'required|max:100',
            'categoria_id'  => 'required|not_in:0'
        ]);
        
        if ($validator->fails()) 
            return redirect('talles/' . $id .'/edit')->withErrors($validator)->withInput();
        */

        // Busco el talle
        $talle = Talle::findOrFail($id);
        
        // Actualizo el talle
        $talle->update($request->except(['_method', '_token']));

        return redirect('/talles')->with('talle_actualizado', 'Talle actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $talle = Talle::findOrFail($id);

        $talle->delete();

        return redirect('/talles/')->with('talle_eliminado', 'Talle con nombre ' . $talle->nombre . ' eliminado');
    }

    public function cargarTallesDeGenero(Request $request)
    {
        // Busco los talles de la categoria
        $talles = Talle::where('genero_id', $request->genero)->select(['id', 'nombre'])->get();

        echo json_encode(array('talles' => $talles));
    }

    /*public function cargarTallesDeCategoria(Request $request)
    {
        // Busco los talles de la categoria
        $talles = Talle::where('categoria_id', $request->categoria)->select(['id', 'nombre'])->get();

        echo json_encode(array('talles' => $talles));
    }*/
}

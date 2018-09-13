<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Categoria;
use Illuminate\Support\Facades\Validator;

class CategoriasController extends Controller
{
    use SoftDeletes;

    public function __construct()
    {
        $this->middleware('section:categorias');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Busco todos los padres
        $categorias = Categoria::whereNull('padre_id')->get();

        return view('categorias.listar')->with('categorias', $categorias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::whereNull('padre_id')->get();

        return view('categorias.create', array('categorias' => $categorias));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valido el input
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|max:100',
        ]);
        
        if ($validator->fails())
            return redirect('categorias/create')->withErrors($validator)->withInput();
        
        // Creo el tipoTalle
        $categoria = Categoria::create($request->all());
        
        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $categoria);

        if ($validator) {
            if ($validator->fails())
                return redirect('categorias/create')->withErrors($validator)->withInput();
        }

        return redirect('/categorias/')->with('categoria_creada', 'Categoría con nombre ' . $request->nombre . ' creado');
    }
    
    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     * @param $request
     * @param $validator
     * @param $tipoTalle
     * @return mixed
     */
    private function subirYGuardarArchivoSiHay($request, $validator, $tipoTalle)
    {
        if (isset($request->archivo) && count($request->archivo) > 0) {
            $archivo = $this->subirArchivo($request);

            if ($archivo['url']) {
                $tipoTalle->archivo = $archivo['url'];
                $tipoTalle->save();
            } else {
                $validator->errors()->add('archivo', $archivo['err']);

                return $validator;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoria = Categoria::where('id', $id)->select(['id', 'nombre', 'padre_id'])->first();
        $categoria->load([
            'CategoriaPadre' => function($query) {
                $query->select(['id', 'nombre']);
            }
        ]);

        return view('categorias.show')->with('categoria', $categoria);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categorias = Categoria::where('id', '<>', $id)->get();

        return view('categorias.edit', array('categoria' => $categoria, 'categorias' => $categorias));
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
        // Valido el input
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|max:100',
        ]);
        
        if ($validator->fails()) 
            return redirect('categorias/' . $id .'/edit')->withErrors($validator)->withInput();

        // Busco el tipoTalle
        $categoria = Categoria::findOrFail($id);
        
        // Actualizo el tipoTalle
        $categoria->update($request->except(['_method', '_token']));

       // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $categoria);

        if ($validator) {
            if ($validator->fails())
                return redirect('categorias/create')->withErrors($validator)->withInput();
        }

        return redirect('/categorias')->with('categoria_actualizado', 'TipoTalle actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        $categoria->delete();

        return redirect('/categorias/')->with('categoria_eliminada', 'Categoria con nombre ' . $categoria->nombre . ' eliminada');
    }

        /**
     * Subir un archivo
     * @param Request $request
     * @return JSON
     */
    public function subirArchivo(Request $request)
    {
        $directorio_destino = 'uploads/archivos/';
        $nombre_original    = $request->archivo->getClientOriginalName();
        $extension          = $request->archivo->getClientOriginalExtension();
        $nombre_archivo     = rand(111111,999999) .'_'. time() . "_.". $extension;

        if ($request->archivo->isValid()) {
            if ($request->archivo->move($directorio_destino, $nombre_archivo)) {
                $url = $directorio_destino . $nombre_archivo;
                $error = false;
            } else {
                $url = false;
                $error = "No se pudo mover el archivo";
            }
        } else {
            $url = false;
            $error = $request->archivo->getErrorMessage();
        }

        return array('url' => $url, 'err' => $error);
    }


}

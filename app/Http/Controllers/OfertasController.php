<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Oferta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OfertasController extends Controller
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
        $ofertas = Oferta::all();
        return view('ofertas.listar')->with('ofertas', $ofertas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ofertas.create');
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
            'descripcion' => 'required|max:500',
            'archivo'     => 'required|max:1000|mimes:jpg,jpeg,png,gif',
            'fecha'       => 'required|date',
            'password'    => 'required|confirmed|min:6',
            'domicilio'   => 'required',
            'email'       => 'required|email|max:100',
            'telefono'    => 'required'
        ]);
        
        if ($validator->fails())
            return redirect('ofertas/create')->withErrors($validator)->withInput();
        
        // Creo el oferta
        $oferta = Oferta::create($request->all());
        
        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $oferta);

        if ($validator) {
            if ($validator->fails())
                return redirect('ofertas/create')->withErrors($validator)->withInput();
        }

        return redirect('/ofertas/')->with('oferta_creado', 'Oferta con nombre ' . $request->nombre . ' creado');
    }
    
    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     * @param $request
     * @param $validator
     * @param $oferta
     * @return mixed
     */
    private function subirYGuardarArchivoSiHay($request, $validator, $oferta)
    {
        if (isset($request->archivo) && count($request->archivo) > 0) {
            $archivo = $this->subirArchivo($request);

            if ($archivo['url']) {
                $oferta->archivo = $archivo['url'];
                $oferta->save();
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
        $oferta = Oferta::findOrFail($id);
        return view('ofertas.show')->with('oferta', $oferta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $oferta = Oferta::findOrFail($id);
        return view('ofertas.edit')->with('oferta', $oferta);
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
            'descripcion' => 'required|max:500',
            'archivo'     => 'required|max:1000|mimes:jpg,jpeg,png,gif',
            'fecha'       => 'required|date',
            'password'    => 'required|confirmed|min:6',
            'domicilio'   => 'required',
            'email'       => 'required|email|max:100',
            'telefono'    => 'required'
        ]);
        
        if ($validator->fails()) 
            return redirect('ofertas/' . $id .'/edit')->withErrors($validator)->withInput();

        // Busco el oferta
        $oferta = Oferta::findOrFail($id);
        
        // Actualizo el oferta
        $oferta->update($request->except(['_method', '_token']));

       // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $oferta);

        if ($validator) {
            if ($validator->fails())
                return redirect('ofertas/create')->withErrors($validator)->withInput();
        }

        return redirect('/ofertas')->with('oferta_actualizado', 'Oferta actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oferta = Oferta::findOrFail($id);

        $oferta->delete();

        return redirect('/ofertas/')->with('oferta_eliminado', 'Oferta con nombre ' . $oferta->nombre . ' eliminado');
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

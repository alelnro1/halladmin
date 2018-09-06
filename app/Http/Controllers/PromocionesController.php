<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Promocion;

class PromocionesController extends Controller
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
        $promociones = Promocion::all();
        return view('promociones.listar')->with('promociones', $promociones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('promociones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validarPromocion($request);

        $archivo = $this->subirArchivo($request);

        $promocion = Promocion::create($request->all());

        $promocion->archivo = $archivo;
        $promocion->save();

        return redirect('/promociones/')->with('promocion_creado', 'Promocion con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promocion = Promocion::findOrFail($id);
        return view('promociones.show')->with('promocion', $promocion);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promocion = Promocion::findOrFail($id);
        return view('promociones.edit')->with('promocion', $promocion);
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
        $this->validarPromocion($request);

        $promocion = Promocion::findOrFail($id);

        $archivo = $this->subirArchivo($request);

        $promocion->update($request->all());

        $promocion->archivo = $archivo;
        $promocion->save();

        return redirect('/promociones/' . $promocion->id)->with('promocion_actualizado', 'Promocion actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promocion = Promocion::findOrFail($id);

        $promocion->delete();

        return redirect('/promociones/')->with('promocion_eliminado', 'Promocion con nombre ' . $promocion->nombre . ' eliminado');
    }

    private function validarPromocion($request)
    {
        $this->validate($request, [
            'nombre'      => 'required|max:100',
            'descripcion' => 'required|max:500',
            'archivo'     => 'required|max:1000|mimes:jpg,jpeg,png,gif',
            'fecha'       => 'required|date',
            'password'    => 'required|confirmed|min:6',
            'domicilio'   => 'required',
            'email'       => 'required|email|max:100',
            'telefono'    => 'required'
        ]);
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

        return $url;
    }

}
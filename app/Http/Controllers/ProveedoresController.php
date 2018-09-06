<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Proveedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProveedoresController extends Controller
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
        $proveedores = Proveedor::where('usuario_id', Auth::user()->id)->get();

        return view('proveedores.listar')->with('proveedores', $proveedores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
                'nombre'      => 'required|max:100',
                'descripcion' => 'max:200',
                'archivo'     => 'mimes:jpg,image/jpeg,png,gif',
                'email'       => 'email|max:100'
            ]
        );

        if ($validator->fails()) {
            return redirect('proveedores/create')->withErrors($validator)->withInput();
        }

        // Le agrego el usuario actual al proveedor (El proveedor es de un usuario)
        $request->request->add(['usuario_id' => Auth::user()->id]);

        // Creo el proveedor
        $proveedor = Proveedor::create($request->all());

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $proveedor);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('proveedores/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/proveedores/')->with('proveedor_creado', 'Proveedor con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->load(
            [
            'Articulos' => function ($query) {
                $query->with(
                    [
                    'DatosArticulo',
                    'Local' => function ($query) {
                        $query->select(['id', 'nombre']);
                    }
                    ]
                );
            },
            ]
        );

        $articulos = $proveedor->Articulos;

        return view('proveedores.show', ['proveedor' => $proveedor, 'articulos' => $articulos]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit')->with('proveedor', $proveedor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
            'nombre'      => 'required|max:100',
            'descripcion' => 'required|max:200',
            'archivo'     => 'mimes:jpg,image/jpeg,png,gif',
            'email'       => 'email|max:100',
            'telefono'    => 'required'
            ]
        );

        // Busco el local
        $local = Proveedor::findOrFail($id);

        // Actualizo el local
        $local->update($request->except(['_method', '_token']));

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $local);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('proveedores/' . $id . '/edit')->withErrors($validator)->withInput();
            }
        }

        return redirect('/proveedores')->with('proveedor_actualizado', 'Proveedor actualizado');
    }

    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     *
     * @param  $request
     * @param  $validator
     * @param  $local
     * @return mixed
     */
    private function subirYGuardarArchivoSiHay($request, $validator, $local)
    {
        if (isset($request->archivo) && count($request->archivo) > 0) {
            $archivo = $this->subirArchivo($request);

            if ($archivo['url']) {
                $local->archivo = $archivo['url'];
                $local->save();
            } else {
                $validator->errors()->add('archivo', $archivo['err']);

                return $validator;
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $proveedor->delete();

        return redirect('/proveedores/')->with('proveedor_eliminado', 'Proveedor con nombre ' . $proveedor->nombre . ' eliminado');
    }

        /**
     * Subir un archivo
         *
     * @param  Request $request
     * @return JSON
     */
    public function subirArchivo(Request $request)
    {
        $directorio_destino = 'uploads/archivos/';
        $nombre_original    = $request->archivo->getClientOriginalName();
        $extension          = $request->archivo->getClientOriginalExtension();
        $nombre_archivo     = rand(111111, 999999) .'_'. time() . "_.". $extension;

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

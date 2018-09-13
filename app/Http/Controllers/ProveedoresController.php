<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedoresRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;

class ProveedoresController extends BaseController
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
    public function store(ProveedoresRequest $request)
    {
        // Le agrego el usuario actual al proveedor (El proveedor es de un usuario)
        $request->request->add(['usuario_id' => Auth::user()->id]);

        // Creo el proveedor
        $proveedor = Proveedor::create($request->all());

        // Si se trató de guardar una foto para el local, validarla y subirla
        $this->subirYGuardarArchivoSiHay($request, $proveedor);

        return redirect('/proveedores/')->with('proveedor_creado', 'Proveedor con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        $proveedor->load([
            'Articulos' => function ($query) {
                $query->with([
                    'DatosArticulo',
                    'Local' => function ($query) {
                        $query->select(['id', 'nombre']);
                    }
                ]);
            },
        ]);

        $articulos = $proveedor->Articulos;

        return view('proveedores.show', ['proveedor' => $proveedor, 'articulos' => $articulos]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit')->with('proveedor', $proveedor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProveedoresRequest $request, Proveedor $proveedor)
    {
        // Valido el input
        /*$validator = Validator::make(
            $request->all(), [
                'nombre' => 'required|max:100',
                'descripcion' => 'required|max:200',
                'archivo' => 'mimes:jpg,image/jpeg,png,gif',
                'email' => 'email|max:100',
                'telefono' => 'required'
            ]
        );*/

        // Busco el local
        //$local = Proveedor::findOrFail($id);

        // Actualizo el local
        $proveedor->update($request->except(['_method', '_token']));

        // Si se trató de guardar una foto para el local, validarla y subirla
        /*$validator = $this->subirYGuardarArchivoSiHay($request, $validator, $local);


        if ($validator) {
            if ($validator->fails()) {
                return redirect('proveedores/' . $id . '/edit')->withErrors($validator)->withInput();
            }
        }*/

        $extension = $request->archivo->getClientOriginalExtension();
        $nombre_archivo = rand(111111, 999999) . '_' . time() . "_." . $extension;

        $path = $request->archivo->storeAs('uploads', $nombre_archivo);

        $proveedor->archivo = $path;
        $proveedor->save();

        return redirect(route('proveedores'))->with('proveedor_actualizado', 'Proveedor actualizado');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
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
        $nombre_original = $request->archivo->getClientOriginalName();
        $extension = $request->archivo->getClientOriginalExtension();
        $nombre_archivo = rand(111111, 999999) . '_' . time() . "_." . $extension;

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

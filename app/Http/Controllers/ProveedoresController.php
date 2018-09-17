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

        // Si se trató de guardar una foto, validarla y subirla
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
        // Actualizo el local
        $proveedor->update($request->except(['_method', '_token']));

        // Si se trató de guardar una foto, validarla y subirla
        $this->subirYGuardarArchivoSiHay($request, $proveedor);

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

        return redirect(route('proveedores'))
            ->with('proveedor_eliminado', 'Proveedor ' . $proveedor->nombre . ' eliminado');
    }
}

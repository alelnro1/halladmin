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
        $local = $this->getLocal();

        $proveedores = $local->getProveedores();

        return view('proveedores.listar', [
            'proveedores' => $proveedores
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtengo el negocio actual
        $negocio = $this->getNegocio();

        // Obtenemos los proveedores del negocio actual
        $proveedores = $negocio->getProveedores();

        $negocio_tiene_proveedores = count($proveedores) > 0;

        return view('proveedores.create', [
            'proveedores' => $proveedores,
            'negocio_tiene_proveedores' => $negocio_tiene_proveedores
        ]);
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

        // Local actual
        $local_actual_id = $this->getLocalId();

        // Vinculamos al proveedor con el local actual
        $proveedor->locales()->attach($local_actual_id);

        return redirect(route('proveedores'))->with('proveedor_creado', 'Proveedor con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        $articulos = $proveedor->getArticulos();

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
     * @param ProveedoresRequest $request
     * @param Proveedor $proveedor
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
     * @param Proveedor $proveedor
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect(route('proveedores'))
            ->with('proveedor_eliminado', 'Proveedor ' . $proveedor->nombre . ' eliminado');
    }

    /**
     * Asignamos un proveedor del negocio a un local
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function asignar(Request $request)
    {
        $proveedor = Proveedor::find($request->proveedor);

        $local = $this->getLocal();

        $negocio_actual_id = $this->getNegocioId();

        // Si el proveedor pertenece a todo el negocio => lo puedo asignar a un local del negocio
        if ($proveedor->perteneceAlNegocio($negocio_actual_id)) {
            $local->Proveedores()->attach($proveedor);

            return redirect(route('proveedores'))->with(['proveedor_asignado' => true]);
        }

        abort(403);
    }
}

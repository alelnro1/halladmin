<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdministradoresRequest;
use App\Models\Menu;
use App\Models\Negocio;
use App\Models\Precios\PriceList;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Nexmo\Account\Price;

class AdministradoresController extends BaseController
{
    use SoftDeletes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administradores = User::getAdministradores();

        return view('administradores.listar')->with('administradores', $administradores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administradores.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdministradoresRequest $request)
    {
        // Le indico que va a ser admin
        $request->merge([
            'es_admin' => '1',
            'password' => bcrypt($request->password)
        ]);

        // Creo el negocio para poder vincularlo con el usuario
        $negocio = Negocio::create([
            'nombre' => $request->negocio
        ]);

        // Creamos el price list default para el negocio
        PriceList::create([
            'nombre' => 'Default PL',
            'negocio_id' => $negocio->id,
            'es_default' => true,
            'activo' => true
        ]);

        $request->request->add(['negocio_id' => $negocio->id]);

        // Creo el administrador
        $administrador = User::create($request->all());

        // Vincularle todos los menus admins al usuario recien creado
        $this->vincularMenus($administrador);

        $this->subirYGuardarArchivoSiHay($request, $administrador);

        return redirect(route('administradores'))->with('administrador_creado', 'Administrador con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $administrador)
    {
        $administrador = $administrador->cargarRelaciones();

        return view('administradores.show')->with('administrador', $administrador);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $administrador)
    {
        return view('administradores.edit')->with('administrador', $administrador);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdministradoresRequest $request, User $administrador)
    {
        // Actualizo el administrador
        $administrador->update($request->except(['_method', '_token']));

        $this->subirYGuardarArchivoSiHay($request, $administrador);

        return redirect(route('administradores'))->with('administrador_actualizado', 'Administrador actualizado');
    }

    /**
     * Cuando un administrador se crea, se le asocian los menus que podrán ver por ser admins
     *
     * @param $administrador
     */
    private function vincularMenus($administrador)
    {
        // Obtengo los menus para admins
        $menus = Menu::where('admin', true)->get();

        // Le asigno los menus para admins al admin que se creó
        foreach ($menus as $menu) {
            $administrador->Menus()->attach($menu->id);
        }
    }
}
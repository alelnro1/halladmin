<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Negocio;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use App\Administrador;

class AdministradoresController extends Controller
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
    public function store(Request $request)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
            'nombre'      => 'required|max:100',
            'apellido'    => 'required|max:100',
            'password'    => 'required|confirmed|min:6',
            'domicilio'   => 'required',
            'email'       => 'required|email|max:100|unique:users',
            'telefono'    => 'required',
            'negocio'     => 'required|max:100'
            ]
        );
        
        if ($validator->fails()) {
            return redirect('administradores/create')->withErrors($validator)->withInput();
        }

        // Le indico que va a ser admin
        $request->request->add(['es_admin' => '1']);

        $request->request->set('password', bcrypt($request->password));

        // Creo el negocio para poder vincularlo con el usuario
        $negocio = Negocio::create([
            'nombre' => $request->negocio
        ]);

        $request->request->add(['negocio_id' => $negocio->id]);

        // Creo el administrador
        $administrador = User::create($request->all());

        // Vincularle todos los menus admins al usuario recien creado
        $this->vincularMenus($administrador);
        
        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $administrador);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('administradores/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/administradores/')->with('administrador_creado', 'Administrador con nombre ' . $request->nombre . ' creado');
    }
    
    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     *
     * @param  $request
     * @param  $validator
     * @param  $administrador
     * @return mixed
     */
    private function subirYGuardarArchivoSiHay($request, $validator, $administrador)
    {
        if (isset($request->archivo) && count($request->archivo) > 0) {
            $archivo = $this->subirArchivo($request);

            if ($archivo['url']) {
                $administrador->archivo = $archivo['url'];
                $administrador->save();
            } else {
                $validator->errors()->add('archivo', $archivo['err']);

                return $validator;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $administrador = User::getAdministrador($id);

        return view('administradores.show')->with('administrador', $administrador);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $administrador = User::findOrFail($id);
        return view('administradores.edit')->with('administrador', $administrador);
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
            'descripcion' => 'required|max:500',
            'archivo'     => 'max:1000|mimes:jpg,jpeg,png,gif',
            'fecha'       => 'required|date',
            'password'    => 'required|confirmed|min:6',
            'domicilio'   => 'required',
            'email'       => 'required|email|max:100',
            'telefono'    => 'required'
            ]
        );
        
        if ($validator->fails()) { 
            return redirect('administradores/' . $id .'/edit')->withErrors($validator)->withInput();
        }

        // Busco el administrador
        $administrador = User::findOrFail($id);
        
        // Actualizo el administrador
        $administrador->update($request->except(['_method', '_token']));

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $administrador);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('administradores/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/administradores')->with('administrador_actualizado', 'Administrador actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
         $administrador = User::findOrFail($id);

        $administrador->delete();

        return redirect('/administradores/')->with('administrador_eliminado', 'Administrador con nombre ' . $administrador->nombre . ' eliminado');
    }*/

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
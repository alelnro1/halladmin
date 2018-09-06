<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\User;

class UsuariosController extends Controller
{
    use SoftDeletes;

    public function __construct()
    {
        $this->middleware('section:usuarios');

        parent::__construct();

        // Si no tiene locales, redirijo al local para que cree uno
        if (!Auth::user()->tieneAlgunLocal()) {
            return Redirect::to('locales')->with('user_no_tiene_locales', true)->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtengo todos los usuarios del local actual
        $usuarios = User::whereHas(
            'Locales', function ($query) {
                $query->where('local_id', $this->getLocalId())
                    ->where('user_id', '!=', Auth::user()->id);
            }
        )->get();

        return view('usuarios.listar')->with('usuarios', $usuarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtengo los menus que no tienen padre, con sus hijos
        $menus =
            Menu::whereNull('padre_id')
                ->where('nombre', '!=', 'Administradores')
                ->where('nombre', '<>', 'Cambios')
                ->select(['id', 'nombre'])
                ->with('MenusHijos')
                ->get();

        return view('usuarios.create', array('menus' => $menus));
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
            'apellido'    => 'required|max:500',
            'archivo'     => 'max:2000|mimes:jpg,jpeg,png,gif',
            'password'    => 'required|confirmed|min:6',
            'email'       => 'required|email|max:100|unique:users',
            'menus'       => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect('usuarios/create')->withErrors($validator)->withInput();
        }

        // Si hay una contraseña, la actualizo
        if ($request->password != "") {
            $request->request->add(['password' => Hash::make($request->password)]);
        }

        // Le agrego el negocio al local
        $request->request->add(['negocio_id' => Auth::user()->negocio_id]);

        // Creo el usuario
        $usuario = User::create($request->except(['_token', 'menus']));

        // Le asigno los menus al usuario
        $menus = $request->menus;
        $this->asignarMenusAlUsuario($usuario, $menus);

        // Asignar el local actual al usuario
        $usuario->Locales()->attach($this->getLocalId());

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $usuario);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('usuarios/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/usuarios/')->with('usuario_creado', 'Usuario con nombre ' . $request->nombre . ' creado');
    }

    /**
     * Creo las relaciones de los menus con el usuario
     *
     * @param $usuario
     * @param $menus
     */
    private function asignarMenusAlUsuario($usuario, $menus_ids)
    {
        // Recorro los menus
        foreach ($menus_ids as $menu_id){
            $menu = Menu::where('id', $menu_id)->first();
            // Verifico que exista el menu
            if (count($menu) > 0) {
                // Si tiene padre => también se le asigna al usuario
                if ($menu->padre_id != null) {
                    $usuario->Menus()->attach($menu->padre_id);
                }

                // Agrego el menú actual
                $usuario->Menus()->attach($menu_id);

                // Si se le agrega que puede vender, se le agregan los cambios. Regla de Dominio
                if ($menu->nombre == "nueva-venta") {
                    $nuevo_cambio_menu = Menu::where('nombre', 'nuevo-cambio')->first();

                    $usuario->menus()->attach($nuevo_cambio_menu->id);
                    $usuario->menus()->attach($nuevo_cambio_menu->padre_id);
                }
            }
        }
    }

    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     *
     * @param  $request
     * @param  $validator
     * @param  $usuario
     * @return mixed
     */
    private function subirYGuardarArchivoSiHay($request, $validator, $usuario)
    {
        if (isset($request->archivo) && count($request->archivo) > 0) {
            $archivo = $this->subirArchivo($request);

            if ($archivo['url']) {
                $usuario->archivo = $archivo['url'];
                $usuario->save();
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
        $usuario = User::findOrFail($id);
        $usuario->load(
            [
            'Menus' => function ($query) {
                $query->with('MenuPadre', 'MenusHijos');
            },
            'Ventas' => function ($query) {
                $query->with('Usuario');
            }
            ]
        );

        return view('usuarios.show')->with('usuario', $usuario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Busco al usuario
        $usuario = User::findOrFail($id);

        // Le cargo los menus habilitados
        $usuario->load('Menus');

        // Obtengo los menus que no tienen padre, con sus hijos
        $menus =
            Menu::whereNull('padre_id')
                ->where('nombre', '!=', 'Administradores')
                ->where('nombre', '<>', 'Cambios')
                ->select(['id', 'nombre'])
                ->with('MenusHijos')
                ->get();

        return view('usuarios.edit', array('usuario' => $usuario, 'menus' => $menus));
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
            'apellido'    => 'required|max:500',
            'archivo'     => 'max:2000|mimes:jpg,jpeg,png,gif',
            'password'    => 'confirmed|min:6',
            'email'       => 'required|email|max:100|unique:users,email,' . $id,
            'menus'       => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect('usuarios/' . $id .'/edit')->withErrors($validator)->withInput();
        }

        // Busco el usuario
        $usuario = User::findOrFail($id);

        // Si hay una contraseña, la actualizo
        if (trim($request->password) == "") {
            $request->request->remove('password');
        } else {
            $request->request->add(['password' => Hash::make($request->password)]);
        }

        // Actualizo el usuario
        $usuario->update($request->except(['_method', '_token']));

        // Si hay algun menu que no tiene el usuario, borro todos y los vuelvo a crear
        if ($this->cambianLosMenus($request->menus, $usuario)) {
            $this->borrarYVincularMenus($request->menus, $usuario);
        }

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $usuario);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('usuarios/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/usuarios')->with('usuario_actualizado', 'Usuario actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        $usuario->delete();

        return redirect('/usuarios/')->with('usuario_eliminado', 'Usuario con nombre ' . $usuario->nombre . ' eliminado');
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

    /**
     * Se borran todas las categorias asignadas al local y se vuelven a crear
     *
     * @param $categorias
     * @param $local
     */
    private function borrarYVincularMenus($menus, $usuario)
    {
        // Borro los menus habilitados
        $usuario->Menus()->detach();

        // Vuelvo a buscar al usuario para obtener los menus actualizados
        $usuario = User::where('id', $usuario->id)->with('Menus')->first();

        // Le asigno los menus al usuario
        $this->asignarMenusAlUsuario($usuario, $menus);

        return true;
    }

    /**
     * Si quiero agregar o quitar alguna categoría
     */
    private function cambianLosMenus($menus_deseados, $usuario)
    {
        // Inicializo un array que va a tener los menus que el usuario tiene actualmente
        $menus_existentes = [];

        // Recorro y voy agregando a los menus existentes
        foreach ($usuario->Menus as $menu) {
            array_push($menus_existentes, $menu->id);

            // Si tiene padre, agrego al padre tambien
            /*if ($menu->padre_id != null) {
                array_push($menus_existentes, (int)$menu->padre_id);
            }*/
        }

        // Agrego los padres a los deseados, si hay, y si está nuevo cambio, le agrego ventas
        foreach ($menus_deseados as $menu_deseado) {
            $menu = Menu::where('id', $menu_deseado)->select(['padre_id', 'nombre'])->first();

            if ($menu->padre_id != null) {
                array_push($menus_deseados, $menu->padre_id);
            }

            if ($menu->nombre == "nuevo-cambio") {
                // Busco el id de nueva_venta
                $cambios_menu = Menu::where('nombre', 'cambios')->first();

                array_push($menus_deseados, $cambios_menu->id);
            }
        }

        // Verifico si hay algun menu distinta en menus_deseados con existentes
        if ($menus_existentes === array_intersect($menus_existentes, $menus_deseados) 
            && $menus_deseados === array_intersect($menus_deseados, $menus_existentes)
        ) {
            return false;
        }

        return true;
    }
}

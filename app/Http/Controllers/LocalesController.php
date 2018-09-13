<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Local;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('section:locales');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locales = Local::getLocalesConUsuarios();

        return view('locales.listar')->with('locales', $locales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::whereNull('padre_id')->with('CategoriasHijas')->get();

        return view('locales.create', array('categorias' => $categorias));
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
            'archivo'     => 'mimes:jpg,jpeg,png,gif',
            'email'       => 'email|max:100',
            'telefono'    => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect('locales/create')->withErrors($validator)->withInput();
        }

        // Le agrego el negocio al local
        $request->request->add(['negocio_id' => Auth::user()->negocio_id]);

        // Creo el local
        $local = Local::create($request->all());

        // Como el usuario actual puede crear un local => será admin del local creado
        $local->Usuarios()->attach(Auth::user()->id, ['es_admin' => '1']);

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $local);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('locales/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/locales/')->with('local_creado', 'Local creado');
    }

    /**
     * Si hay algun archivo para subir, subirlo y guardar la referencia en la base
     * @param $request
     * @param $validator
     * @param $local
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Local $local)
    {
        return view('locales.show')->with('local', $local);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Local $local)
    {
        $local->load('Categorias');

        // Busco todas las categorias disponibles
        $categorias = Categoria::whereNull('padre_id')->with('CategoriasHijas')->get();

        return view('locales.edit', array('local' => $local, 'categorias' => $categorias));
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
            'archivo'     => 'mimes:jpg,jpeg,png,gif',
            'email'       => 'email|max:100',
            'telefono'    => 'required',
            //'categorias'  => 'required'
            ]
        );

        if ($validator->fails()) {
            return redirect('locales/' . $id . '/edit')->withErrors($validator)->withInput();
        }

        // Busco el local
        $local = Local::findOrFail($id);

        // Cargo los ID's de las categorias del local
        /*$local->load(
            [
            'Categorias' => function ($query) {
                $query->select(['categoria_id']);
            }
            ]
        );*/

        // Actualizo el local
        $local->update($request->except(['_method', '_token']));

        // Si hay alguna categoria que no tiene el local, borro todas y las vuelvo a crear
        /*if ($this->cambianLasCategorias($request->categorias, $local)) {
            $this->borrarYVincularCategorias($request->categorias, $local);
        }*/

        // Si se trató de guardar una foto para el local, validarla y subirla
        $validator = $this->subirYGuardarArchivoSiHay($request, $validator, $local);

        if ($validator) {
            if ($validator->fails()) {
                return redirect('locales/create')->withErrors($validator)->withInput();
            }
        }

        return redirect('/locales/')->with('local_actualizado', 'Local actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Busco el local y si no existe lanzo una excepcion
        $local = Local::findOrFail($id);

        // Elimino el local
        $local->delete();

        // Busco a ver si el usuario tiene algun local y si tiene lo seteo
        $hay_local =
            Local::whereHas(
                'Usuarios', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                }
            )->first();

        // Si hay locales del usuario => pongo la sesión en true
        if (!$hay_local) {
            session(['LOCAL_ACTUAL' => null]);
            session(['HAY_ALGUN_LOCAL' => null]);
        }

        return redirect('/locales/')->with('local_eliminado', 'Local eliminado');
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
    /** DEPRACATED
     * Se borran todas las categorias asignadas al local y se vuelven a crear
     *
     * @param $categorias
     * @param $local
     *
     */
    /*private function borrarYVincularCategorias($categorias, $local)
    {
        // Borro las categorias
        $local->Categorias()->detach();

        // Volver a crear las relaciones
        foreach ($categorias as $categoria) {
            $local->Categorias()->attach($categoria);
        }

        return true;
    }*/

    /**
     * DEPRACATED
     * Si quiero agregar o quitar alguna categoría
     */
    /*private function cambianLasCategorias($categorias_deseadas, $local)
    {
        // Inicializo un array que va a tener las categorias que el local tiene actualmente
        $categorias_existentes = [];

        // Recorro y voy agregando a las categorias existentes
        foreach ($local->Categorias as $categoria) {
            array_push($categorias_existentes, $categoria->categoria_id);
        }

        // Verifico si hay alguna categoria distinta en categorias_deseadas con existentes
        if ($categorias_existentes === array_intersect($categorias_existentes, $categorias_deseadas) 
            && $categorias_deseadas === array_intersect($categorias_deseadas, $categorias_existentes)
        ) {
            return false;
        }

        return true;
    }*/
}
<?php

namespace App\Http\Controllers;

use App\Models\Mercaderia\Articulo;
use App\Models\Categoria;
use App\Models\Mercaderia\DatosArticulo;
use App\Models\Mercaderia\Genero;
use App\Models\Mercaderia\MercaderiaTemporal;
use App\Models\Proveedor;
use App\Models\Mercaderia\Talle;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MercaderiaController extends ArchivosTemporalesController
{
    public function __construct($mercaderia = true)
    {
        // Si viene de /mercaderia* tiene que tener permitido el acceso de mercaderia
        if ($mercaderia) {
            $this->middleware('section:mercaderia');
        }

        parent::__construct();
    }

    /**
     * Vista del ingreso de mercadería
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ingresoForm()
    {
        $ingreso_form = $this->getDatosParaIngresoForm();

        return view(
            'mercaderia.ingreso', [
            'talles' => $ingreso_form['talles'],
            'mercaderias_temporales' => $ingreso_form['mercaderias_temporales'],
            'mercaderia_existente' => $ingreso_form['mercaderia_existente'],
            'proveedores' => $ingreso_form['proveedores'],
            'categorias' => $ingreso_form['categorias'],
            'generos' => $ingreso_form['generos']
        ]);
    }

    /**
     * Obtengo todos los datos para armar el formulario de ingreso de mercadería.
     * Si hay mercaderia temporal se arma el array
     *
     * @return array
     */
    private function getDatosParaIngresoForm()
    {
        // Inicializo el array
        $datos = [];

        $datos['mercaderias_temporales'] = $this->getArrayTemporal(MercaderiaTemporal::class);

        // Busco todos los proveedores del usuario actual
        $datos['proveedores'] = Proveedor::where('usuario_id', Auth::user()->id)->get();

        // Guardo en una sesion los proveedores del usuario
        //session(['PROVEEDORES_DE_USUARIO_' . Auth::user()->id => $proveedores]);

        $datos['categorias'] = Categoria::all();

        $datos['generos'] = Genero::all();

        // Busco los tipos de talles
        $datos['talles'] = Talle::all();

        // Busco todas las mercaderias (ya ingresadas) del local
        $datos['mercaderia_existente'] = $this->getLocal()->getMercaderia();

        return $datos;
    }

    /**
     * Devuelvo un array con la cantidad de articulos temporales
     * y con los articulos en un array aparte
     */
    /*private function getMercaderiasTemporales()
    {
        // Array de return
        $mercaderia = array();

        // Busco la mercaderia temporal del usuario con el local
        $mercaderia_temporal =
            MercaderiaTemporal::where('local_id', $this->getLocalId())
                ->where('user_id', Auth::user()->id)
                ->select(['link'])
                ->first();

        // Si hay algo temporal, lo tengo que cargar
        if ($mercaderia_temporal && $mercaderia_temporal->count() >= 1) {
            // Obtengo el contenido del archivo
            $mercaderia_de_archivo = Storage::get($mercaderia_temporal->link);

            $mercaderia_de_archivo = explode("\n", $mercaderia_de_archivo);

            // Recorro las lineas del archivo
            foreach ($mercaderia_de_archivo as $key => $articulo){
                // Si el articulo no está vacío
                if ($articulo != "") {
                    // Un articulo se guarda por linea asi → codigo:1,descripcion:asd,precio:25
                    $articulo = explode(",", $articulo);

                    foreach ($articulo as $articulo_con_clave) {
                        $articulo_con_clave = explode(":", $articulo_con_clave);
                        $nombre_clave = $articulo_con_clave[0];
                        $valor = $articulo_con_clave[1];

                        $art_datos[$nombre_clave] = $valor;
                    }

                    array_push($mercaderia, $art_datos);
                }
            }
        }

        return $mercaderia;
    }*/

    /**
     * Si existe, se elimina el archivo y el registro de la base de datos de la mercaderia temporal
     */
    /*private function eliminarMercaderiaTemporal()
    {
        $user_id = Auth::user()->id;
        $local_id = $this->getLocalId();

        // Busco el link de la mercaderia temporal del usuario con el local
        $mercaderia_temporal =
            MercaderiaTemporal::where('local_id', $local_id)
                ->where('user_id', $user_id)
                ->select(['id', 'link'])
                ->first();

        // Borro el archivo si existe
        if ($mercaderia_temporal) {
            // Elimino la sesion
            session(['link_mercaderia_temporal_local_de_user_' . $local_id . '_' . $user_id => null]);

            // Elimino el archivo
            Storage::delete($mercaderia_temporal->link);

            // Elimino el registro
            $mercaderia_temporal->delete();
        }
    }*/

    /**
     * Guardo la mercadería, borro la mercaderia temporal
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function procesarIngresoDeMercaderia(Request $request)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
                'codigo.*' => 'required',
                'descripcion.*' => 'required|max:500',
                'genero_id.*' => 'required',
                'categoria_id.*' => 'required',
                'talle_id.*' => 'required|not_in:0',
                'color.*' => 'required',
                'cantidad.*' => 'required|integer'
            ]
        );

        // Hubo un error, tengo que redirigir al formulario de ingreso y procesar todo de nuevo
        if ($validator->fails()) {
            $ingreso_form = $this->getDatosParaIngresoForm();

            return redirect('mercaderia/ingreso')
                ->with('errors', $validator->errors()->all())
                ->with('talles', $ingreso_form['talles'])
                ->with('mercaderias_temporales', $ingreso_form['mercaderias_temporales'])
                ->with('mercaderia_existente', $ingreso_form['mercaderia_existente'])
                ->with('proveedores', $ingreso_form['proveedores'])
                ->with('categorias', $ingreso_form['categorias'])
                ->with('generos', $ingreso_form['generos']);
        }

        // HAY QUE BORRAR EL ARCHIVO DEL STORAGE Y EL REGISTRO DE LA BASE EN LA TABLA mercaderias_temporales
        // Obtengo la mercaderia del local actual
        $mercaderia = $this->getArrayTemporal(MercaderiaTemporal::class);

        // Guardo la mercaderia en la base
        foreach ($mercaderia as $fila_de_archivo_temporal) {
            // Cuando se elimina una fila que no tiene codigo, a veces queda el registro en el
            // artchivo temporal => salteo esa fila
            if ($fila_de_archivo_temporal['codigo'] == "") {
                continue;
            }

            // Busco el articulo con codigo del local con talle, con descripcion
            $datos_articulo =
                DatosArticulo::where('codigo', $fila_de_archivo_temporal['codigo'])
                    ->select(['id'])
                    ->where('local_id', $this->getLocalId())
                    ->first();

            if ($datos_articulo) {
                // Ya existe un articulo con ese codigo => busco el articulo con los parametros recibidos
                $articulo_existente =
                    Articulo::where('datos_articulo_id', $datos_articulo->id)
                        ->where('talle_id', $fila_de_archivo_temporal['talle_id'])
                        ->where('color', $fila_de_archivo_temporal['color'])
                        ->first();

                if ($articulo_existente) {
                    // Si ya existe un articulo con ese codigo, talle y color => actualizo la cantidad
                    $articulo_existente->cantidad = $articulo_existente->cantidad + $fila_de_archivo_temporal['cantidad'];
                    $articulo_existente->save();
                } else {
                    $articulo_existente = $this->crearArticuloConDatosExistentes($fila_de_archivo_temporal, $datos_articulo);
                }
            } else {
                // Reemplazo las comas por puntos para tener decimales
                $fila_de_archivo_temporal['precio'] = str_replace(",", ".", $fila_de_archivo_temporal['precio']);
                $fila_de_archivo_temporal['codigo'] = str_replace(" ", "", $fila_de_archivo_temporal['codigo']);

                // Creo los datos comunes del articulo
                $datos_articulo =
                    DatosArticulo::create([
                        'codigo' => trim($fila_de_archivo_temporal['codigo']),
                        'precio' => $fila_de_archivo_temporal['precio'],
                        'descripcion' => $fila_de_archivo_temporal['descripcion'],
                        'categoria_id' => $fila_de_archivo_temporal['categoria_id'],
                        'genero_id' => $fila_de_archivo_temporal['genero'],
                        'local_id' => $this->getLocalId()
                    ]);

                // No existe un articulo con ese codigo => hay que crearlo
                // Vinculo los datos comunes con el articulo recien creado
                $articulo_existente = $this->crearArticuloConDatosExistentes($fila_de_archivo_temporal, $datos_articulo);
            }

            // Si existe el costo del articulo o el proveedor => Lo relaciono en la tabla
            $this->relacionarArticuloConProveedorYCosto($articulo_existente, $fila_de_archivo_temporal);
        }

        // Elimino el archivo y los registros de la base
        $this->eliminarArchivosTemporales(MercaderiaTemporal::class);

        // Redirijo al listado de artículos
        return redirect('/mercaderia')->with(['mercaderia_ingresada' => 'La mercadería se ingresó correctamente.']);
    }

    /**
     * Se crea el articulo
     *
     * @param $fila_temporal
     * @param $datos_articulo
     * @return Articulo
     */
    private function crearArticuloConDatosExistentes($fila_temporal, $datos_articulo)
    {
        // El articulo con el codigo existe, pero no con talle y color
        return
            Articulo::create([
                'cantidad' => $fila_temporal['cantidad'],
                'color' => $fila_temporal['color'],
                'genero' => $fila_temporal['genero'],
                'local_id' => $this->getLocalId(),
                'talle_id' => $fila_temporal['talle_id'],
                'datos_articulo_id' => $datos_articulo->id
            ]);
    }

    /**
     * Guardamos temporalmente las filas de los articulos cuando están siendo ingresados
     * en caso de que se recargue la página, se le apague la PC o pierda la sesión
     * ESTRUCTURA DE LA FILA DEL ARTICULO:
     *      codigo, descripcion, color, precio, cantidad
     */
    public function guardarFilasTemporalmente(Request $request)
    {
        $archivo = $this->getArchivoTemporalDeLocal(MercaderiaTemporal::class);

        $fila = null;

        foreach ($request->articulos as $articulo) {
            // Reemplazo las comas por puntos para tener decimales
            $articulo['precio'] = str_replace(",", ".", $articulo['precio']);

            $fila .=
                'codigo:' . $articulo['codigo'] . "," .
                'descripcion:' . $articulo['descripcion'] . "," .
                'categoria_id:' . $articulo['categoria_id'] . "," .
                'talle_id:' . $articulo['talle_id'] . "," .
                'color:' . $articulo['color'] . "," .
                'proveedor_id:' . $articulo['proveedor_id'] . "," .
                'genero:' . $articulo['genero'] . "," .
                'precio:' . $articulo['precio'] . "," .
                'costo:' . $articulo['costo'] . "," .
                'existe:' . $articulo['existe'] . "," . // Indica si el codigo que se escribió ya existe
                'cantidad:' . $articulo['cantidad'] . "\n";
        }

        // Creo el archivo
        Storage::disk('local')->put($archivo, $fila);
    }

    /**
     * Desde la vista, cuando se tipea un código, aca buscamos si existen los datos de ese código
     * y si existen los enviamos a la vista para que se carguen en el formulario
     *
     * @param Request $request
     */
    public function cargarArticuloConCodigo(Request $request)
    {
        $codigo = $request->codigo;

        $articulo =
            DatosArticulo::where('codigo', $codigo)
                ->where('local_id', $this->getLocalId())
                ->select(['id', 'precio', 'descripcion', 'categoria_id', 'genero_id'])
                ->with(
                    [
                        'Categoria' => function ($query) {
                            $query->select(['id', 'nombre']);
                        },
                        'Genero'
                    ]
                )
                ->first();

        if ($articulo) {
            echo json_encode(array('articulo' => $articulo));
        } else {
            echo json_encode(array('articulo' => ""));
        }
    }

    /**
     * Relaciono el articulo con el proveedor en la tabla articulo_proveedor, y si ademas puso el costo, lo guardo
     *
     * @param $articulo_existente
     * @param $fila_de_archivo_temporal
     */
    private function relacionarArticuloConProveedorYCosto($articulo, $fila_de_archivo_temporal)
    {
        // Busco el proveedor
        $proveedor = Proveedor::where('id', $fila_de_archivo_temporal['proveedor_id'])->first();

        // Creo la relacion entre el proveedor y el articulo
        $articulo->Proveedores()->attach(
            $proveedor, [
                'costo' => $fila_de_archivo_temporal['costo'],
                'cantidad' => $fila_de_archivo_temporal['cantidad'],
                'cantidad_remanente' => $fila_de_archivo_temporal['cantidad'],
                'local_id' => $this->getLocalId()
            ]
        );
    }
}

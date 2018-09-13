<?php

namespace App\Http\Controllers;

use App\Models\Mercaderia\Articulo;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Cambio;

class CambiosController extends VentasController
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Muestro el formulario con los articulos del local para seleccionar el articulo a devolver
     *
     * @return $this
     */
    public function ingresarArticuloACambiarForm()
    {
        // Obtengo si hay alguna sesion con el articulo a cambiar
        $articulo_a_cambiar = $this->getArticuloParaCambiar();

        if ($articulo_a_cambiar) {
            return $this->seleccionarArticulosParaCambiarForm();
        }

        // Busco todos los articulos que tiene el local
        $articulos = Articulo::where('local_id', $this->getLocalId())->get();

        return view('cambios.nuevo')->with('articulos', $articulos);
    }

    /**
     * Se selecciona el articulo a cambiar y se devuelve por ajax
     *
     * @param Request $request
     */
    public function seleccionarArticuloACambiar(Request $request)
    {
        // Obtengo el articulo a cambiar
        $articulo = Articulo::where('id', $request->articulo)->first();

        // Creo la sesion con el articulo a cambiar
        session(['CAMBIO_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id => $articulo]);

        // Redirijo a la pantalla de ventas
        echo json_encode(['success' => true]);
        //return redirect('ventas/nueva-venta', ['cambio' => true, 'articulo_a_cambiar' => $articulo]);
    }

    /**
     * Se muestra el formulario con todos los articulos para vender
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seleccionarArticulosParaCambiarForm()
    {
        // Obtengo los articulos para el listado
        $articulos = $this->getArticulosConTalleYLocal();

        // Obtengo el articulo a cambiar
        $articulo_a_cambiar = $this->getArticuloParaCambiar();

        $articulos = $this->marcarArticulosTemporales($articulos);

        return view('cambios.seleccionar-articulos-venta', ['articulos' => $articulos, 'articulo_a_cambiar' => $articulo_a_cambiar]);
    }

    /**
     * Se elimina la sesion y se manda a seleccionar un articulo para cambiar
     *
     * @return CambiosController
     */
    public function cambiarArticuloADevolver()
    {
        $this->eliminarSesionConArticuloAModificar();

        return redirect('cambios/nuevo-cambio');
    }

    public function index()
    {
        $cambios =
            Cambio::where('local_id', $this->getLocalId())
                ->with(
                    [
                        'Venta' => function ($query) {
                            $query->select(['id', 'nro_orden', 'monto_total', 'user_id']);
                            $query->with(
                                [
                                    'Articulos',

                                    'Usuario' => function ($query) {
                                        $query->select(['id', 'nombre', 'apellido']);
                                    }
                                ]
                            );
                        },
                        'Articulo' => function ($query) {
                            $query->select(['id', 'datos_articulo_id']);
                            $query->with(
                                [
                                    'DatosArticulo' => function ($query) {
                                        $query->select('id', 'descripcion');
                                    }
                                ]
                            );
                        }
                    ]
                )
                ->select(['id', 'venta_id', 'created_at', 'articulo_id'])
                ->orderBy('created_at')
                ->get();

        return view('cambios.index', ['cambios' => $cambios]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cambio = Cambio::findOrFail($id);
        return view('cambios.show')->with('cambio', $cambio);
    }

    function eliminarSesionConArticuloAModificar()
    {
        session(['CAMBIO_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id => null]);
    }

    public function getArticuloParaCambiar()
    {
        return session('CAMBIO_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id);
    }
}

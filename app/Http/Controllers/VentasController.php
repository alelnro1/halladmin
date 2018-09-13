<?php

namespace App\Http\Controllers;

use App\Models\Mercaderia\Articulo;
use App\Models\Cambio;
use App\Models\Cliente;
use App\Models\Mercaderia\DatosArticulo;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaCancelada;
use App\Models\Ventas\VentaTemporal;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class VentasController extends MercaderiaController
{
    public function __construct()
    {
        parent::__construct(false);
    }

    public function index()
    {
        // Obtengo todas las ventas del local actual
        $ventas =
            Venta::where('local_id', $this->getLocalId())
                ->with('Usuario')
                ->orderBy('created_at', 'DESC')
                ->get();

        return view('ventas.listar', ['ventas' => $ventas]);
    }

    public function show($nro_orden)
    {
        // Busco la venta por nro de orden
        $venta = Venta::getVenta($nro_orden);

        // Articulo a cambiar (solo para cambios)
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Si el articulo para cambiar es null, no viene de un cambio
        // Pero la venta puede tener asociado un cambio => lo busco
        if ($venta->Cambio) {
            $articulo_a_cambiar = $venta->Cambio->Articulo;
        }

        return view('ventas.show', ['venta' => $venta, 'articulo_a_cambiar' => $articulo_a_cambiar]);
    }

    /**
     * Muestro el formulario para realizar una nueva venta
     *
     * @param  $cambio si es true, se viene de un cambio y se mostrará el saldo disponible arriba
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function nuevaVentaForm()
    {
        $this->getArchivoTemporalDeLocal(VentaTemporal::class);

        // Obtengo los articulos del local con los talles y sus detalles
        $articulos = $this->getArticulosConTalleYLocal();

        // Verifico de los articulos existentes los que se marcaron, y la cantidad elegida
        $articulos = $this->marcarArticulosTemporales($articulos);

        return view('ventas.nueva-venta', ['articulos' => $articulos]);
    }

    protected function getArticulosConTalleYLocal()
    {
        $articulos = Articulo::getArticulosParaVentaForm();

        return $articulos;
    }

    public function ventasCanceladas()
    {
        $ventas_canceladas =
            VentaCancelada::where('local_id', $this->getLocalId())
                ->with(['Usuario', 'Cliente'])
                ->get();

        return view('ventas.listado-ventas-canceladas', ['ventas_canceladas' => $ventas_canceladas]);
    }

    /**
     * Guardamos temporalmente las filas de los articulos cuando están siendo ingresados
     * en caso de que se recargue la página, se le apague la PC o pierda la sesión
     * ESTRUCTURA DE LA FILA DEL ARTICULO:
     *      codigo, color, talle, cantidad
     */
    public function guardarFilasTemporalmente(Request $request)
    {
        // Obtengo los articulos
        $articulos = $request->articulos;

        // De aca se obtiene el link con el archivo
        $archivo = $this->getArchivoTemporalDeLocal(VentaTemporal::class);

        $fila = null;

        // Si no hay articulos, no hago nada
        if (count($articulos) <= 0) {
            echo json_encode(['success' => true]);
        } else {
            foreach ($request->articulos as $articulo) {
                $fila .=
                    'codigo:' . $articulo['codigo'] . "," .
                    'color:' . $articulo['color'] . "," .
                    'genero:' . $articulo['genero'] . "," .
                    'cantidad:' . $articulo['cantidad'] . "," .
                    'subtotal:' . $articulo['subtotal'] . "," .
                    'descuento:' . $articulo['descuento'] . "," .
                    'talle:' . $articulo['talle'] . "\n";
            }
        }

        // Creo el archivo
        Storage::disk('local')->put($archivo, $fila);

        echo json_encode(['success' => true, 'url' => 'datos-de-cliente']);
    }

    /**
     * Se busca la venta por usuario y por local y se procede a pedirle los datos del cliente
     *
     * @param $local_id
     */
    public function pedirDatosDeCliente()
    {
        $clientes = Cliente::where('negocio_id', Auth::user()->negocio_id)->get();

        return view('clientes.pedir-datos-de-venta', array('clientes' => $clientes));
    }

    /**
     * Se previsualiza la venta mostrando los items que se quieren comprar y el cliente (si se eligió)
     */
    public function previsualizarVenta()
    {
        // Obtengo al id del cliente al que se le venderá
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Busco al cliente
        $cliente = Cliente::where('id', $cliente_id)->first();

        Log::info('PREVISUALIZAR VENTA. Voy a buscar los articulos para vender.');

        // Inicializo la variable de articulos, donde estarán los articulos reales a vender
        $articulos = $this->armarArticulosParaVenderDeTemporales();

        Log::critical('PREVISUALIZAR VENTA. Vienen los articulos a vender.');
        Log::critical(print_r($articulos, true));

        // Creo el numero de orden
        $nro_orden = $this->generarNroOrden();
        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_NRO_ORDEN' => $nro_orden]);

        // Creo la sesion con los articulos para que sea de mas facil acceso
        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS' => $articulos]);

        // Calculo el total
        $total = $this->calcularTotalVenta($articulos);

        // Calculo la cantidad total de articulos a vender
        $cantidad_articulos = $this->calcularCantidadArticulos($articulos);

        // Articulo a cambiar
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        return view('ventas.previsualizar', [
            'cliente' => $cliente,
            'articulos' => $articulos,
            'total' => $total,
            'nro_orden' => $nro_orden,
            'cantidad_articulos' => $cantidad_articulos,
            'articulo_a_cambiar' => $articulo_a_cambiar
        ]);
    }

    /**
     * Se obtienen los articulos temporales para armar el array de articulos definitivos a vender
     * @return array
     */
    public function armarArticulosParaVenderDeTemporales()
    {
        // Inicializo la variable de articulos, donde estarán los articulos reales a vender
        $articulos = [];

        Log::info('armarArticulosParaVenderDeTemporales. Voy a buscar el Array Temporal');

        // Obtengo los artículos a vender
        $articulos_temporales = $this->getArrayTemporal(VentaTemporal::class);

        Log::critical('armarArticulosParaVenderDeTemporales. Vienen los articulos temporales del array temporal');
        Log::critical(print_r($articulos_temporales, true));

        // Busco los articulos de verdad que vamos a vender
        foreach ($articulos_temporales as $articulo_temporal) {

            // Primero busco los datos del articulo y filtro por local por si están duplicados los códigos a través de distintos locales
            $datos_articulo = DatosArticulo::getArticuloConCodigo($articulo_temporal['codigo']);

            // Ahora sobre los datos del articulo, filtro por color y talle
            $articulo = Articulo::filtrarArticuloDeDatosId($datos_articulo, $articulo_temporal);

            // Guardo la cantidad de articulos a vender
            $articulo['cantidad_a_vender'] = $articulo_temporal['cantidad'];
            $articulo['subtotal'] = $articulo_temporal['subtotal'];
            $articulo['descuento'] = $articulo_temporal['descuento'];

            array_push($articulos, $articulo);
        }

        return $articulos;
    }

    /**
     * Genero un numero de orden que no exista en la base de datos
     *
     * @return int
     */
    private function generarNroOrden()
    {
        while (true) {
            // Genero un numero random
            $nro_orden = mt_rand();

            // Verifico que no exista en la base
            $venta = Venta::where('nro_orden', $nro_orden)->count();

            // Si no existe => devuelvo ese nro de orden
            if ($venta <= 0) {
                return $nro_orden;
            }
        }
    }

    /**
     * Se calcula el precio total de toda la venta
     *
     * @param $articulos
     */
    private function calcularTotalVenta($articulos)
    {
        // Articulo a cambiar
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Inicializo el total
        $total = 0;

        // El total es el precio del articulo por la cantidad vendida
        foreach ($articulos as $articulo) {
            //$total = $total + ($articulo->DatosArticulo->precio * $articulo->cantidad_a_vender);
            if (isset($articulo->subtotal)) {
                $total = $total + $articulo->subtotal;
            } else {
                $total = $total + $articulo['subtotal'];
            }
        }

        // Si hay un articulo para cambiar, al total de la venta se le resta el saldo a favor
        if ($articulo_a_cambiar) {
            $total = $total - $articulo_a_cambiar->DatosArticulo->precio;
        }

        return $total;
    }

    /**
     * Se suman el total de articulo vendidos
     *
     * @param $articulos
     */
    private function calcularCantidadArticulos($articulos)
    {
        // Inicializo el total
        $total = 0;

        // El total es el precio del articulo por la cantidad vendida
        foreach ($articulos as $articulo) {
            // TODO: Acoplar con la estructura del articulo. Se recibe como array y deberia ser objeto. Viene de aplicarDescuento
            /*if (isset($articulo->cantidad_a_vender)) {
                $total = $total + $articulo->cantidad_a_vender;
            } else {
                $total = $total + $articulo['cantidad'];
            }*/
            $total = $total + $articulo['cantidad_a_vender'];
        }

        return $total;
    }

    /**
     * Se setea el medio de pago
     * @param Request $request
     */
    public function setMedioDePagoYFactura(Request $request)
    {
        // Tengo dos medios de pagos. 1) Efectivo = true 2) Tarjeta = false
        if ($request->medio === true) {
            $medio = "Efectivo";
        } else {
            $medio = "Tarjeta";
        }

        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_MEDIO_DE_PAGO' => $medio]);

        //$medio_de_pago = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_MEDIO_DE_PAGO');

        return json_encode(array('valid' => true));
    }

    /**
     * Se concreta la venta, se registra, se resta stock y se vinculan los articulos con la venta
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function concretarVenta()
    {
        // Obtengo los articulos
        $articulos = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS');

        // Obtengo el cliente si existe
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Obtengo el nro de orden
        $nro_orden = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_NRO_ORDEN');

        // Obtengo el medio de pago si es que hay alguno
        $medio_de_pago = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_MEDIO_DE_PAGO');

        // Si no hay articulos es porque hubo un problema de sesiones => regenero
        if (!$articulos) {
            $articulos = $this->armarArticulosParaVenderDeTemporales();
        }

        // Verifico que no se haya creado un nro_orden con el mismo numero mientras se realizaba la venta actual
        if (Venta::where('nro_orden', $nro_orden)->count() > 0) {
            $nro_orden = $this->generarNroOrden();
        }

        // Calculo el monto total de la venta
        $total_venta = $this->calcularTotalVenta($articulos);

        // Calculo la cantidad total de artículos a vender
        $cantidad_articulos_a_vender = $this->calcularCantidadArticulos($articulos);

        // Si hay un cambio, se crea
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Si hay un cliente actualizo el saldo
        if ($cliente_id) {
            // Busco el cliente
            $cliente = Cliente::findOrFail($cliente_id);
            $cliente->actualizaTuSaldo($total_venta);
        }

        // 1) Se registra la venta
        $venta =
            Venta::create(
                [
                    'cliente_id' => $cliente_id,
                    'monto_total' => $total_venta,
                    'local_id' => $this->getLocalId(),
                    'user_id' => Auth::user()->id,
                    'cantidad_articulos' => $cantidad_articulos_a_vender,
                    'nro_orden' => $nro_orden,
                    'medio_de_pago' => $medio_de_pago
                ]
            );

        // Si vengo de un cambio => lo creo
        if ($articulo_a_cambiar) {
            $cambio =
                Cambio::create(
                    [
                        'articulo_id' => $articulo_a_cambiar->id,
                        'local_id' => $this->getLocalId(),
                        'user_id' => Auth::user()->id,
                        'venta_id' => $venta->id
                    ]
                );
        }

        // 2) Para todos los articulos
        foreach ($articulos as $articulo) {
            $cantidad_a_vender = $articulo->cantidad_a_vender;

            // 2.1) Se resta el stock
            $articulo->cantidad = $articulo->cantidad - 1;

            // 2.2) Se guarda en la tabla articulo_venta con el precio y la cantidad
            $articulo->ventas()->attach(
                $venta->id, [
                    'precio' => $articulo->DatosArticulo->precio,
                    'cantidad' => $cantidad_a_vender,
                    'descuento' => $articulo->descuento,
                    'subtotal' => $articulo->subtotal,
                    'categoria_id' => $articulo->DatosArticulo->categoria_id,
                    'talle_id' => $articulo->talle_id,
                    'genero_id' => $articulo->genero_id
                ]
            );

            // Elimino el campo que contiene la cantidad a vender del articulo para poder guardar
            unset($articulo->cantidad_a_vender);

            // Elimino el campo que contiene el subtotal de cada articulo para poder guardar
            unset($articulo->subtotal);

            // Elimino el campo que contiene el descuento de cada articulo para poder guardar
            unset($articulo->descuento);

            $articulo->update(
                [
                    'cantidad' => $articulo->cantidad - $cantidad_a_vender
                ]
            );
        }

        // 2) Se eliminan los archivos temporales y las sesiones
        $this->eliminarArchivosTemporalesYSesiones();

        return redirect('ventas/nueva-venta')->with('venta-concretada', true);
    }

    /**
     * Se eliminan los archivos y mercaderias temporales llamando al metodo y las sesiones
     */
    private function eliminarArchivosTemporalesYSesiones()
    {
        // Elimino la mercaderia temporal y el registro de la base de datos
        $this->eliminarArchivosTemporales(VentaTemporal::class);

        // Se elimina la sesion que contiene al cliente al cual se le va a vender
        session(['VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID' => null]);

        // Se elimina la sesion que contiene todos los articulos temporales
        session(['VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS' => null]);

        // Se elimina la sesion que contiene el cambio
        session(['CAMBIO_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id => null]);
    }

    /**
     * Cancelo la venta eliminando todos los archivos y sesiones
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancelarVenta(Request $request)
    {
        // Obtengo el cliente
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Creo el registro de la venta cancelada
        VentaCancelada::create(
            [
                'user_id' => Auth::user()->id,
                'local_id' => $this->getLocalId(),
                'cliente_id' => $cliente_id,
                'motivo' => $request->motivo
            ]
        );


        // Se eliminan todos los datos temporales de la venta
        $this->eliminarArchivosTemporalesYSesiones();

        echo json_encode(['success' => true]);
    }

    /**
     * Se manda a imprimir la venta
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function imprimir()
    {
        // Obtengo al id del cliente al que se le venderá
        $cliente_id = session('VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID');

        // Busco al cliente
        $cliente = Cliente::where('id', $cliente_id)->first();

        // Busco los articulos que se vieron en la previsualizacion
        $articulos = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_ARTICULOS');

        // Calculo la cantidad total de artículos a vender
        $cantidad_articulos_a_vender = $this->calcularCantidadArticulos($articulos);

        // Calculo el total
        $total = $this->calcularTotalVenta($articulos);

        // Articulo a cambiar
        $cambio = new CambiosController();
        $articulo_a_cambiar = $cambio->getArticuloParaCambiar();

        // Obtengo el nro de orden
        $nro_orden = session('VENTA_LOCAL_ID_' . $this->getLocalId() . '_USER_ID_' . Auth::user()->id . '_NRO_ORDEN');

        return view(
            'print.venta', [
                'articulos' => $articulos,
                'articulo_a_cambiar' => $articulo_a_cambiar,
                'cliente' => $cliente,
                'total' => $total,
                'nro_orden' => $nro_orden,
                'cantidad_articulos' => $cantidad_articulos_a_vender
            ]
        );
    }

    /**
     * Se busca dentro de los articulos del local, los que hayan sido marcados para vender
     *
     * @param  $articulos
     * @param  $venta_temporal
     * @return mixed
     */
    protected function marcarArticulosTemporales($articulos)
    {
        $venta_temporal = $this->getArrayTemporal(VentaTemporal::class);

        // Verifico los articulos que fueron seleccionados y los marco
        foreach ($articulos as $articulo_existente) {
            foreach ($venta_temporal as $articulo_a_vender) {
                if ($articulo_existente->DatosArticulo->codigo == $articulo_a_vender['codigo']
                    && $articulo_existente->Talle->nombre  == $articulo_a_vender['talle']
                    && $articulo_existente->color == $articulo_a_vender['color']
                    && $articulo_existente->DatosArticulo->Genero->nombre == $articulo_a_vender['genero']
                ) {
                    $articulo_existente['seleccionado'] = true;
                    $articulo_existente['cantidad_a_vender'] = $articulo_a_vender['cantidad'];

                    $articulo_existente['subtotal'] =
                        $articulo_a_vender['cantidad'] * $articulo_existente->DatosArticulo->precio;

                    $articulo_existente['descuento'] = $articulo_a_vender['descuento'];


                    // Si hay descuento, lo aplico
                    if ($articulo_a_vender['descuento'] > 0) {
                        $articulo_existente['subtotal'] =
                            $articulo_existente['subtotal'] -
                            $articulo_existente['subtotal'] * ($articulo_existente['descuento'] / 100);
                    }
                }
            }
        }

        return $articulos;
    }

    /**
     * Se actualizan los precios temporales por los descuentos
     *
     * @param Request $request
     */
    function aplicarDescuento(Request $request)
    {
        $venta_temporal = $this->getArrayTemporal(VentaTemporal::class);
        $articulos_con_descuento = $request->articulos_con_descuento;

        foreach ($venta_temporal as $key => $articulo_temporal) {
            foreach ($articulos_con_descuento as $articulo_descuento) {
                // Si el codigo es el mismo, le actualizo el precio
                if ($articulo_temporal['codigo'] == $articulo_descuento['codigo']) {
                    $venta_temporal[$key]['subtotal'] = $articulo_descuento['subtotal'];
                }
            }

        }

        // TODO: Mejorar este llamado a la funcion guardarFilasTemporalmente
        $request->request->add(['articulos' => $venta_temporal]);

        $this->guardarFilasTemporalmente($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Classes\Ventas\CancelarVenta;
use App\Classes\Ventas\ConcretarVenta;
use App\Classes\Ventas\ImprimirVenta;
use App\Classes\Ventas\PrevisualizarVenta;
use App\Classes\Ventas\Ventas;
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

class VentasController extends Ventas
{
    public function index()
    {
        // Obtengo todas las ventas del local actual
        $ventas = Venta::getUltimasVentasParaHome();

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

    /**
     * Obtenemos todas las ventas del local
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ventasCanceladas()
    {
        $ventas_canceladas = $this->getLocal()->getVentas();

        $ventas_canceladas->load([
            'Usuario',
            'Cliente'
        ]);

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
        $clientes = $this->getNegocio()->getClientes();

        return view('clientes.pedir-datos-de-venta', [
            'clientes' => $clientes
        ]);
    }

    /**
     * Se previsualiza la venta mostrando los items que se quieren comprar y el cliente (si se eligió)
     */
    public function previsualizarVenta()
    {
        $previsualizacion = new PrevisualizarVenta();

        return $previsualizacion->previsualizar();
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
        $concretar_venta = new ConcretarVenta();

        $concretar_venta->concretar();

        return redirect('ventas/nueva-venta')->with('venta-concretada', true);
    }

    /**
     * Cancelo la venta eliminando todos los archivos y sesiones
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancelarVenta(Request $request)
    {
        $cancelar_venta = new CancelarVenta();

        $cancelar_venta->cancelar($request);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Se manda a imprimir la venta
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function imprimir()
    {
        $imprimir_venta = new ImprimirVenta();

        return $imprimir_venta->imprimir();
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
    public function aplicarDescuento(Request $request)
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

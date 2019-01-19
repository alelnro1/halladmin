<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->tieneAlgunLocal()) {
            $cantidad_ventas = $cantidad_cambios = $cantidad_ventas_canceladas = $cantidad_usuarios = 0;
            $ventas = [];
            $grafico_lineal = $grafico_pie = false;
        } else {
            $local = $this->getLocal();

            // Obtenemos las ventas
            $ventas = $local->getVentas(20);

            // Cantidad Ventas
            $cantidad_ventas = $local->getCantidadDeVentas();

            // Cantidad Ventas Canceladas
            $cantidad_ventas_canceladas = $local->getCantidadDeVentasCanceladas();

            // Cantidad Cambios
            $cantidad_cambios = $local->getCantidadDeCambios();

            // Cantidad Usuarios
            $cantidad_usuarios = $local->getCantidadDeUsuarios();

            // Voy a crear un grafico de todas las ventas y cambios
            $graficos = new GraficosController();
            $graficos = $graficos->crearGraficoVentasYCambiosLinealYPieHome();

            $grafico_lineal = $graficos['lineal'];
            $grafico_pie = $graficos['pie'];
        }

        $local_nombre = "";

        if (session('LOCAL_ACTUAL') != null) {
            $local_nombre = session('LOCAL_ACTUAL')->nombre;
        }

        return view(
            'home', [
                'grafico_lineal' => $grafico_lineal,
                'grafico_pie' => $grafico_pie,
                'cantidad_ventas' => $cantidad_ventas,
                'cantidad_cambios' => $cantidad_cambios,
                'cantidad_ventas_canceladas' => $cantidad_ventas_canceladas,
                'cantidad_usuarios' => $cantidad_usuarios,

                'local_nombre' => $local_nombre,

                'ventas' => $ventas
            ]
        );
    }
}

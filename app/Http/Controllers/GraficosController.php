<?php

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\Ventas\Venta;
use App\Models\Ventas\VentaCancelada;
use Illuminate\Http\Request;

use App\Http\Requests;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class GraficosController extends Controller
{
    public function crearGraficoVentasYCambiosLinealYPieHome()
    {
        $fecha_desde = date("Y-m-1");
        $fecha_hasta = date("Y-m-d", strtotime("today"));

        // Obtengo las ventas agrupadas por dia desde el principio de mes
        $venta = new Venta();
        $venta_cancelada = new VentaCancelada();
        $cambio = new Cambio();

        $ventas_por_dia = $venta->dameVentasParaGraficoDeHome($fecha_desde, $fecha_hasta);
        $ventas_canceladas_por_dia = $venta_cancelada->dameVentasCanceladasParaGraficoDeHome($fecha_desde, $fecha_hasta);
        $cambios_concretados_por_dia = $cambio->dameCambiosParaGraficoDeHome($fecha_desde, $fecha_hasta);

        $graficos = [];

        $graficos['pie'] = $this->crearGraficoVentarYCambiosPieHome($ventas_por_dia, $ventas_canceladas_por_dia, $cambios_concretados_por_dia);

        $graficos['lineal'] = $this->crearGraficoVentasYCambiosLinealHome($fecha_hasta, $ventas_por_dia, $ventas_canceladas_por_dia, $cambios_concretados_por_dia);

        return $graficos;
    }

    public function crearGraficoVentarYCambiosPieHome($ventas_por_dia, $ventas_canceladas_por_dia, $cambios_concretados_por_dia)
    {
        $ventas = count($ventas_por_dia);
        $ventas_canceladas = count($ventas_canceladas_por_dia);
        $cambios = count($cambios_concretados_por_dia);

        $total_acciones = $ventas + $ventas_canceladas + $cambios;

        // Si hay mas de 2 acciones => puedo armar el gráfico
        if ($total_acciones > 1) {
            $datatable = Lava::DataTable();

            $datatable
                ->addStringColumn('Accion')
                ->addNumberColumn('Porcentaje')
                ->addRow(['Ventas', $ventas])
                ->addRow(['Ventas Canceladas', $ventas_canceladas])
                ->addRow(['Cambios', $cambios]);

            return
                Lava::PieChart('PIE', $datatable, [
                    'title'  => 'Porcentajes',
                    'is3D'   => false,
                    'slices' => [
                        ['offset' => 0.2],
                        ['offset' => 0.25],
                        ['offset' => 0.3]
                    ]
                ]);
        }
    }

    public function crearGraficoVentasYCambiosLinealHome($fecha_hasta, $ventas_por_dia, $ventas_canceladas_por_dia, $cambios_concretados_por_dia)
    {
        if (count($ventas_por_dia) > 1 || count($ventas_canceladas_por_dia) > 1 || count($cambios_concretados_por_dia) > 1) {
            $datatable = Lava::DataTable();
            $datatable->addNumberColumn('Dia');
            $datatable->addNumberColumn('Ventas');
            $datatable->addNumberColumn('Ventas Canceladas');
            $datatable->addNumberColumn('Cambios');

            Lava::NumberFormat([
                'decimalSymbol' => 'string',
                'fractionDigits' => 0,
                'groupingSymbol' => 'string',
                'negativeColor' => 'string',
                'pattern' => 'string',
                'prefix' => 'string',
                'suffix' => 'string'
            ]);

            // Agregamos las ventas concretadas al grafico
            $this->agregarDatosAlGraficoLinealDeHome($datatable, $ventas_por_dia, $ventas_canceladas_por_dia, $cambios_concretados_por_dia);

            return
                Lava::LineChart('LINE', $datatable, [
                    'title' => 'Rendimiento Mensual',
                    'vAxis' => [
                        'title' => 'Total'
                    ],
                    'xAxis' => [
                        'gridlines' => ['count' => date("d", strtotime($fecha_hasta))],
                        'format' => '##',
                    ],
                    'hAxis' => [
                        'title' => 'Día'
                    ]
                ]);

            /*$filter = Lava::NumberRangeFilter(0, [
                'ui' => [
                    'labelStacking' => 'vertical'
                ]
            ]);

            $control = Lava::ControlWrapper($filter, 'control');
            $chart = Lava::ChartWrapper($pieChart, 'chart');

            return Lava::Dashboard('Donuts')->bind($control, $chart);*/
        }

        return false;

    }

    /**
     * No todos los dias se realizan acciones (ventas, ventas canceladas o cambios)
     * Esta funcion agrega los dias que no hubo acciones con la cantidad 0, los ordena
     * y devuelve para poder realizar el gráfico correcto
     * @param $datatable
     * @param $array
     * @return mixed
     */
    private function agregarDiasSinAccionAlGraficoHome($datatable, $array)
    {
        // Dias que si se hizo una accion
        $dias_con_datos = [];

        // Guardo los dias que hubo acciones
        foreach ($array as $value) {
            array_push($dias_con_datos, $value['dia']);
        }

        for ($dia_del_mes = 1; $dia_del_mes <= date("d"); $dia_del_mes++) {
            // Si ese dia no hubo ventas no va a estar en el array => hay que agregarlo al datatable
            if (!in_array($dia_del_mes, $dias_con_datos)) {
                $dia_sin_accion['dia'] = $dia_del_mes;
                $dia_sin_accion['cantidad'] = 0;

                array_push($array, $dia_sin_accion);

            }
        }

        sort($array);

        return $array;
    }

    /**
     * A partir del agrupamiento de la cantidad de acciones (ventas, canceladas o cambios)
     * obtenemos los días que realmente ocurrió al menos una acción
     * @param $acciones_por_dia
     * @return array
     */
    private function obtenerDiasConAcciones($acciones_por_dia)
    {
        // Inicializo el array de dias que hubo ventas
        $dias_con_acciones = [];

        foreach ($acciones_por_dia as $acciones_de_dia) {
            $dia_accion['dia'] = (int) date("d", strtotime($acciones_de_dia->dia));
            $dia_accion['cantidad'] = $acciones_de_dia->cantidad;

            array_push($dias_con_acciones, $dia_accion);
        }

        return $dias_con_acciones;
    }
    /**
     * Agrego las ventas, ventas canceladas y cambios al grafico
     * @param $datatable
     * @param $ventas
     * @param $ventas_canceladas
     * @param $cambios
     */
    private function agregarDatosAlGraficoLinealDeHome($datatable, $ventas, $ventas_canceladas, $cambios)
    {
        $dias_con_ventas = $this->obtenerDiasConAcciones($ventas);
        $dias_con_ventas_canceladas = $this->obtenerDiasConAcciones($ventas_canceladas);
        $dias_con_cambios = $this->obtenerDiasConAcciones($cambios);

        // Agrego los dias que no hubo ventas al datatable para que aparezcan en 0
        $ventas = $this->agregarDiasSinAccionAlGraficoHome($datatable, $dias_con_ventas);
        $ventas_canceladas = $this->agregarDiasSinAccionAlGraficoHome($datatable, $dias_con_ventas_canceladas);
        $cambios = $this->agregarDiasSinAccionAlGraficoHome($datatable, $dias_con_cambios);

        foreach ($ventas as $key => $value) {
            $datatable->addRow([
                $value['dia'],
                $value['cantidad'],
                $ventas_canceladas[$key]['cantidad'],
                $cambios[$key]['cantidad']
            ]);
        }
    }
}

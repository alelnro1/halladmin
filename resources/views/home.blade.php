@extends('layouts.app')

@section('page-header', Session::get('LOCAL_ACTUAL')->nombre)
@section('page-description', 'Resumen mensual')

@if (Auth::user()->tieneAlgunLocal())
    {{-- Esta sección deberá mostrar una alerta si no se abrió la caja si hay al menos 1 local --}}
    @section('alerta-caja-sin-abrir')
        @include('commons.alerta-caja-sin-abrir')
    @endsection
@endif

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('content')
    @include('reportes.numeros')

    @if (isset($grafico_lineal) && $grafico_lineal)
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Gráficos</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-7 col-sm-7 col-lg-7">
                        <div id="line-chart"></div>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-lg-3">
                        <div id="pie-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        {!! \Lava::render('PieChart', 'PIE', 'pie-chart') !!}
        {!! \Lava::render('LineChart', 'LINE', 'line-chart') !!}
    @endif

    @if (Auth::user()->tieneAlgunLocal())
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Últimas Ventas</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="box-body">
                @if (count($ventas) > 0)
                    @include('ventas.listado-ventas')
                @else
                    No hay ventas
                @endif
            </div>
        </div>
    @else
        <div class="box box-info">
            <div class="box-body">
                Para operar, comience creando su primer local haciendo
                <a href="{{ route('locales.create') }}"> click aquí</a>
            </div>
        </div>
    @endif
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/reportes.js') }}"></script>
@stop

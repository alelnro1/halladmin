@extends('layouts.app')

@section('page-header', $LOCAL_NOMBRE)
@section('page-description', 'Resumen')

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
                <a href="{{ url('locales/create') }}"> click aquí</a>
            </div>
        </div>
    @endif

    {{--<div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Monthly Recap Report</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-wrench"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center">
                                <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="salesChart" style="height: 180px; width: 704px;" height="180" width="704"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>Goal Completion</strong>
                            </p>

                            <div class="progress-group">
                                <span class="progress-text">Add Products to Cart</span>
                                <span class="progress-number"><b>160</b>/200</span>

                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                            <div class="progress-group">
                                <span class="progress-text">Complete Purchase</span>
                                <span class="progress-number"><b>310</b>/400</span>

                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-red" style="width: 80%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                            <div class="progress-group">
                                <span class="progress-text">Visit Premium Page</span>
                                <span class="progress-number"><b>480</b>/800</span>

                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-green" style="width: 80%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                            <div class="progress-group">
                                <span class="progress-text">Send Inquiries</span>
                                <span class="progress-number"><b>250</b>/500</span>

                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
                                </div>
                            </div>
                            <!-- /.progress-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                                <h5 class="description-header">$35,210.43</h5>
                                <span class="description-text">TOTAL REVENUE</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                <h5 class="description-header">$10,390.90</h5>
                                <span class="description-text">TOTAL COST</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                                <h5 class="description-header">$24,813.53</h5>
                                <span class="description-text">TOTAL PROFIT</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block">
                                <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                                <h5 class="description-header">1200</h5>
                                <span class="description-text">GOAL COMPLETIONS</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>--}}
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/reportes.js') }}"></script>
@stop

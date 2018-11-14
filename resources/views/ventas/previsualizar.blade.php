@extends('layouts.app')

@section('page-description', 'Previsualización de Venta')

@section('page-header', 'Ventas')
@section('page-description', 'Previsualización')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Venta</a></li>
    <li class="active">Previsualización</li>
@stop

@section('content')
    <div class="alert callout callout-info no-print">
        <h4><i class="fa fa-info"></i> Nota:</h4>
        Confirme el cliente y los artículos seleccionados para finalizar la venta
    </div>

    <section class="invoice no-margin">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> {{ strtoupper(session('LOCAL_ACTUAL')->nombre) }}
                    <small class="pull-right">Fecha: {{ date("d/m/Y", time()) }}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>

        <div class="row invoice-info">
            <div class="col-xs-4 invoice-col">
                @if ($cliente)
                    <strong>Cliente</strong>
                    <address>
                        {{ $cliente->nombre }} {{ $cliente->apellido }}<br>
                        Teléfono: {{ $cliente->telefono }}<br>
                        Email: {{ $cliente->email }}<br>
                        <a href="{{ url('ventas/datos-de-cliente') }}">Modificar Cliente</a>
                    </address>
                @endif
            </div>

            <div class="col-xs-4 invoice-col">
                @if ($articulo_a_cambiar)
                    <strong>Artículo a Cambiar</strong><br>
                    Descripción: {{ $articulo_a_cambiar->DatosArticulo->descripcion }}<br>
                    Precio: ${{ number_format($articulo_a_cambiar->DatosArticulo->precio, 2) }}<br>

                    <a href="{{ url('cambios/nuevo') }}" id="modificar-articulo">Modificar Artículo</a>
                @endif
            </div>

            @include('ventas.comunes.col-totales')
        </div>

        <hr>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                @if ($articulo_a_cambiar)
                    <h4>
                        Vender
                    </h4>
                @endif
                <table class="table table-striped" id="articulos">
                    <thead>
                    <tr>
                        <th>Cód</th>
                        <th>Cant</th>
                        <th>Artículo</th>
                        <th>Talle</th>
                        <th>Color</th>
                        <th>Género</th>
                        <th class="text-right">Precio Unitario</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right descuento">Descuento</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($articulos) > 0)
                        @foreach($articulos as $articulo)
                            <tr>
                                <td class="codigo">{{ $articulo->DatosArticulo->codigo }}</td>
                                <td>{{ $articulo->cantidad_a_vender }}</td>
                                <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                                <td>{{ $articulo->Talle->nombre }}</td>
                                <td>{{ $articulo->color }}</td>
                                <td>{{ $articulo->DatosArticulo->Genero->nombre }}</td>
                                <td class="text-right">
                                    ${{ number_format($articulo->DatosArticulo->precio, 2) }}
                                </td>
                                <td class="text-right subtotal"
                                    data-subtotal="{{ $articulo->subtotal }}"
                                    data-subtotal-dto="{{ $articulo->DatosArticulo->precio * $articulo->cantidad_a_vender }}">
                                    ${{ number_format($articulo->subtotal, 2) }}
                                </td>
                                <td class="descuento col-xs-2 text-right">
                                    {{ number_format($articulo->descuento, 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>

        <div class="row no-print">
            <div class="col-xs-12">
                <button href="{{ route('ventas.cancelar') }}"
                        id="cancelar-venta"
                        class="btn btn-danger">
                    Cancelar
                </button>

                <a href="{{ route('ventas.nueva') }}"
                   class="btn btn-default">
                    Modificar Artículos
                </a>

                @if (!$cliente)
                    <a href="{{ route('ventas.datos-de-cliente') }}"
                       class="btn btn-default">
                        Agregar Cliente
                    </a>
                @endif

                <a href="#"
                   type="button"
                   id="concretar-venta"
                   class="btn btn-success pull-right">
                    <i class="fa fa-credit-card"></i> Finalizar
                </a>

                <a href="{{ route('ventas.imprimir') }}"
                   target="_blank"
                   type="button"
                   class="btn btn-primary pull-right"
                   style="margin-right: 5px;">
                    <i class="fa fa-print"></i> Imprimir
                </a>

                <!-- Trigger the modal with a button -->
                <button type="button" class="btn btn-primary pull-right"
                        style="margin-right: 5px;"
                        data-toggle="modal" data-target="#myModal">
                    Generar Factura
                </button>

                {{--<a href="{{ route('ventas.generar-factura') }}"
                   type="button"
                   class="btn btn-primary pull-right"
                   style="margin-right: 5px;">
                    <i class="fa fa-print"></i> Generar Factura
                </a>--}}
            </div>
        </div>
    </section>

    @include('ventas.nueva.previsualizar.modal-cancelar')

    @include('afip.datos-para-facturar')
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('js/ventas/nueva/previsualizar.js') }}"></script>
@stop
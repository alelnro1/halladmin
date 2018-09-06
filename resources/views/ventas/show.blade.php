@extends('layouts.app')

@section('page-header', 'Ventas')
@section('page-description', '#' . $venta->nro_orden)

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Venta</a></li>
    <li class="active">#{{ $venta->nro_orden }}</li>
@stop

@section('content')
    <section class="invoice no-margin">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> {{ strtoupper(session('LOCAL_ACTUAL')->nombre) }}
                    <small class="pull-right">
                        Fecha de la Venta: {{ date("d/m/Y", strtotime($venta->created_at)) }}
                        <br>
                        Vendedor:
                        <a href="{{ url('usuarios/' . $venta->user_id) }}">
                            {{ $venta->Usuario->nombre }} {{ $venta->Usuario->apellido }}
                        </a>
                    </small>
                </h2>
            </div>
            <!-- /.col -->
        </div>

        <div class="row invoice-info">
            <div class="col-xs-4 invoice-col">
                @if($venta->Cliente != null)
                    Cliente
                    <address>
                        <strong>{{ $venta->Cliente->nombre }} {{ $venta->Cliente->apellido }}</strong><br>
                        Teléfono: {{ $venta->Cliente->telefono }}<br>
                        Email: {{ $venta->Cliente->email }}
                    </address>
                @endif
            </div>

            <div class="col-xs-4 invoice-col">
                @if ($articulo_a_cambiar)
                    <strong>Artículo Cambiado</strong><br>
                    Descripción: {{ $articulo_a_cambiar->DatosArticulo->descripcion }}<br>
                    Precio: ${{ number_format($articulo_a_cambiar->DatosArticulo->precio, 2) }}<br>

                    <a href="{{ url('articulos/' . $articulo_a_cambiar->id) }}">Ver Artículo</a>
                @endif
            </div>

            <!-- /.col -->
            <div class="col-xs-4 invoice-col text-right">
                <b>Orden #{{ $venta->nro_orden }}</b><br>
                <br>
                <b>Cantidad de Artículos:</b> {{ $venta->cantidad_articulos }} <br>
                <b>Total:</b> ${{ number_format($venta->monto_total, 2) }}<br>
            </div>
            <!-- /.col -->
        </div>

        <hr>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Cód</th>
                        <th>Cant</th>
                        <th>Artículo</th>
                        <th>Talle</th>
                        <th>Color</th>
                        <th>Género</th>
                        <th class="text-right">Precio Unitario</th>
                        <th class="text-right">Precio S/Dto</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Descuento</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($venta->Articulos) > 0)
                        @foreach($venta->Articulos as $articulo)
                            <tr>
                                <td>{{ $articulo->DatosArticulo->codigo }}</td>
                                <td>{{ $articulo->pivot->cantidad }}</td>
                                <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                                <td>{{ $articulo->Talle->nombre }}</td>
                                <td>{{ $articulo->color }}</td>
                                <td>{{ $articulo->DatosArticulo->Genero->nombre }}</td>
                                <td class="text-right">
                                    ${{ number_format($articulo->pivot->precio, 2) }}
                                </td>
                                <td class="text-right">
                                    ${{ number_format($articulo->pivot->precio * $articulo->pivot->cantidad, 2) }}
                                </td>
                                <td class="text-right">
                                    ${{ number_format($articulo->pivot->subtotal, 2) }}
                                </td>
                                <td class="text-right">
                                    @if ($articulo->pivot->descuento != 0)
                                        {{ number_format($articulo->pivot->descuento, 2) }}%
                                    @else
                                        No aplica
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>

        <div class="box-footer">
            <div class="pull-xs-left">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-sm btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop
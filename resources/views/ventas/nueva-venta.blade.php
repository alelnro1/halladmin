@extends('layouts.app')

@section('site-name', 'Nueva Venta')

@section('page-header', session('LOCAL_NOMBRE'))
@section('page-description', 'Nueva Venta')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Ventas</a></li>
    <li class="active">Nueva</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-body">
            @if (Session::has('venta-concretada'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    La venta se ha concretado
                </div>
            @endif

            @if (Session::has('venta-cancelada'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    La venta ha sido cancelada
                </div>
            @endif

            <div class="help-block">Haga click o presione sobre los artículos que desea vender</div>

            @if(isset($articulo_a_cambiar) && $articulo_a_cambiar)
                <div class="help-block">
                    Saldo Disponible por artículo a cambiar:

                    ${{ number_format($articulo_a_cambiar->DatosArticulo) }}
                </div>
            @endif

            @if(count($articulos) > 0)
                <table class="table table-bordered table-hover display responsive no-wrap" id="articulos" width="100%">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th>Talle</th>
                        <th>Género</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                    </tr>

                    </thead>

                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th>Talle</th>
                        <th>Género</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>

                    <tbody>
                    @foreach ($articulos as $articulo)
                        <tr class="@if($articulo['seleccionado']) selected @endif">
                            <td>{{ $articulo->id }}</td>
                            <td class="col-codigo">{{ $articulo->getCodigo() }}</td>
                            <td>{{ $articulo->getDescripcion() }}</td>
                            <td class="col-color">{{ $articulo->color }}</td>
                            <td class="col-talle">{{ $articulo->getNombreTalle() }}</td>
                            <td class="col-genero">{{ $articulo->getNombreGenero() }}</td>
                            <td class="text-right col-precio" data-precio="{{ $articulo->getPrecio() }}">
                                ${{ number_format($articulo->getPrecio(), 2) }}
                            </td>
                            <td class="col-cantidad col-xs-1" data-id="{{ $articulo->id }}">
                                <input type="number"
                                       min="0"
                                       id="cantidad_{{ str_random(8) }}"
                                       class="cantidad form-control"
                                       @if($articulo->cantidad_a_vender != null)
                                       value="{{ (int) $articulo->cantidad_a_vender }}"
                                       @else
                                       value="1"
                                        @endif>
                            </td>
                            <td class="col-subtotal" data-subtotal="{{ $articulo->subtotal }}">
                                @if ($articulo->subtotal)
                                    ${{ number_format($articulo->subtotal, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="col-dto col-xs-2" data-porc-dto="">
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control porc-dto"
                                           step="1.00"
                                           min="0.00"
                                           max="100.00"
                                           @if ($articulo->descuento)
                                           value="{{ $articulo->descuento }}"
                                           @else
                                           value="0.00"
                                           @endif
                                           name="porc-dto">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center">No hay artículos para vender</div>
            @endif
            {{ csrf_field() }}
        </div>

        @if(count($articulos) > 0)
            <div class="box-footer">
                <div class="col-xs-5">
                    <input class="btn btn-danger" type="submit" id="cancelar-venta" value="Cancelar venta">
                </div>

                <div class="text-right">
                    <input class="btn btn-primary" type="submit" id="confirmar-articulos-venta" value="Confirmar artículos">
                </div>
            </div>
        @endif
    </div>
@stop

@section('javascript')
    <script type="text/javascript" src="http://halladmin.com/js/jquery.validate.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/ventas/listar.js') }}"></script>
@stop
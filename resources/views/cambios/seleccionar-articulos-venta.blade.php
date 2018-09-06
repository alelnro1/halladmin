@extends('layouts.app')

@section('site-name', 'Nuevo Cambio')
@section('page-header', 'Cambio')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Cambios</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-body">
            @if (Session::has('cambio-concretado'))
                <div class="callout callout-success">

                    El cambio se ha concretado
                </div>
            @endif

            @if (Session::has('cambio-cancelado'))
                <div class="callout callout-success">
                    El cambio ha sido cancelada
                </div>
            @endif

            <div class="alert callout callout-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

                Haga click o presione sobre los artículos que desea vender.
                Verifique que el artículo a devolver es el que seleccionó
            </div>

            @if ($articulo_a_cambiar)
                <div class="callout callout-warning">
                    Artículo a Cambiar: <strong>{{ $articulo_a_cambiar->DatosArticulo->descripcion }}</strong>
                    <br>
                    Saldo a favor: <strong>${{ number_format($articulo_a_cambiar->DatosArticulo->precio, 2) }}</strong>
                    <br>
                    <a href="{{ url('cambios/nuevo') }}" id="modificar-articulo">Modificar Artículo</a>
                </div>
            @endif

                <table class="display nowrap table table-bordered table-hover" id="articulos" width="100%">
                <thead>
                    <tr>
                        <th></th>
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
                @if(count($articulos) > 0)
                    @foreach ($articulos as $articulo)
                        <tr class="@if($articulo['seleccionado']) selected @endif">
                            <td>{{ $articulo->id }}</td>
                            <td class="col-codigo">{{ $articulo->DatosArticulo->codigo }}</td>
                            <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                            <td class="col-color">{{ $articulo->color }}</td>
                            <td class="col-talle">{{ $articulo->Talle->nombre }}</td>
                            <td class="col-genero">{{ $articulo->DatosArticulo->Genero->nombre }}</td>
                            <td class="text-right col-precio" data-precio="{{ $articulo->DatosArticulo->precio }}">
                                ${{ number_format($articulo->DatosArticulo->precio, 2) }}
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
                @else
                    <tr>
                        <td class="text-center" colspan="6">No hay artículos para vender</td>
                    </tr>
                @endif
                </tbody>
            </table>
            {{ csrf_field() }}
        </div>

        <div class="box-footer">
            <div class="text-right">
                <input class="btn btn-primary" type="submit" id="confirmar-articulos-venta" value="Confirmar artículos">
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript" src="{{ asset('js/jquery.validate.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/ventas/listar.js') }}"></script>
@stop
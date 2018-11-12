@extends('layouts.app')

@section('site-name', 'Ingreso de Mercadería')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('page-description', 'Ingreso de Mercadería')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <style>
        /*#cover {position: fixed; height: 100%; width: 100%; top:0; left: 0; background: #000; z-index:9999;
            font-size: 60px; text-align: center; padding-top: 200px; color: #fff;
        }*/
    </style>
    <link rel="stylesheet" href="{{ asset('css/loading-cover.css') }}">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Mercadería</a></li>
    <li class="active">Ingreso</li>
@stop

@section('content')
    <!--<div id="cover">Cargando...</div>-->

    <div class="box box-primary">
        <div class="panel-body">
        {{--<div class="spinner-container">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>--}}

        @if(Session::has('errors'))
            <div class="callout callout-danger">
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                No hemos podido procesar los artículos porque ha habido un error de datos. Por favor, revíselos.
            </div>
        @endif

        <form action="{{ route('mercaderia.procesar-ingreso') }}" method="POST" id="mercaderia-form">
            {{ csrf_field() }}
            <div class="box-body" style="padding: 10px 0">
                <table class="table table-bordered table-hover" id="nueva-mercaderia">
                    <thead>
                        <tr>
                            <th class="col-xs-1">Código</th>
                            <th class="col-xs-2">Descripción</th>
                            <th class="col-xs-1">Género</th>
                            <th class="col-xs-1">
                                Categoría
                            </th>
                            <th class="col-xs-1">Talle</th>
                            <th class="col-xs-1">Color</th>
                            <th class="col-xs-1">Proveedor</th>
                            <th class="col-xs-1">
                                Costo&nbsp;
                                <i class="fa fa-question-circle" aria-hidden="true" id="tooltip-costo"
                                   title="Este campo representa el costo de compra. Es utilizado en Información Contable"></i>
                            </th>
                            <th class="col-xs-1">Precio</th>
                            <th class="col-xs-1">Cantidad</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($mercaderias_temporales) > 0)
                            @foreach($mercaderias_temporales as $articulo)
                                @include('mercaderia.articulo-con-datos', [
                                    'articulo' => $articulo,
                                    'proveedores' => $proveedores,
                                    'mercaderia_existente' => $mercaderia_existente
                                ])
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="text-right margin-bottom">
                <a href="#" class="btn btn-success" id="agregar-articulo">+ Agregar artículo</a>
            </div>

            <div class="box-footer">
                <div class="text-left col-xs-6">
                    <a href="#" id="buscar-articulo" class="btn btn-default btn-sm">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        Buscar artículo
                    </a>
                </div>

                <div class="text-right">
                    <input class="btn btn-primary" type="submit" id="ingresar-mercaderia" value="Ingresar Mercadería">
                </div>
            </div>

            <div id="dialog-articulos" style="display:none;">
                <table class="table table-bordered table-hover" id="mercaderia-existente">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Color</th>
                            <th>Talle</th>
                            <th>Categoria</th>
                            <th></th>
                        </tr>
                    </thead>

                    @if(count($mercaderia_existente) > 0)
                    <tbody>
                            @foreach ($mercaderia_existente as $articulo)
                                <tr>
                                    <td>{{ $articulo->DatosArticulo->codigo }}</td>
                                    <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                                    <td>{{ $articulo->color }}</td>
                                    <td>{{ $articulo->Talle->nombre }}</td>
                                    <td>{{ $articulo->DatosArticulo->Categoria->nombre }}</td>
                                    <td>
                                        <a href="#" class="elegir-articulo btn btn default btn-sm" data-articulo="{{ $articulo }}">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;
                                            Elegir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                    @endif
                </table>
            </div>
        </form>
    </div>
    </div>
@stop

@section('javascript')
    <script type="x-template" id="nueva-fila-articulo">
        @include('mercaderia.nueva-fila-articulo', ['proveedores' => $proveedores])
    </script>

    <script type="text/javascript" src="{{ asset('js/jquery.validate.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/mercaderia/ingreso.js') }}"></script>
    <script>
        $(window).on('load', function() {
            //$(".spinner-container").fadeOut(200);

            $('.categoria_id, .talle_id, .proveedor_id').tooltip({
                disabled: true
            });
        });
    </script>
@stop
@extends('layouts.app')

@section('site-name', 'Viendo a articulo')

@section('page-description', 'Artículo')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Artículos</a></li>
    <li class="active">{{ $articulo->DatosArticulo->descripcion }}</li>
@stop

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <style>
        tr.group,
        tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Articulo <b><i>{{ $articulo->DatosArticulo->descripcion }}</i></b>
        </div>

        <div class="panel-body">

            @if(isset($articulo->archivo) && $articulo->archivo != "")
                <div class="text-center margin-bottom">
                    <img src="/{{ $articulo->archivo }}" height="250" />
                </div>
            @endif

            <fieldset>
                <legend>Datos</legend>

                <table class="table table-striped task-table" style="margin-bottom: 20px;">
                    <tr>
                        <td><strong>Código</strong></td>
                        <td>{{ $articulo->DatosArticulo->codigo }}</td>
                    </tr>
                    <tr>
                        <td><strong>Descripción</strong></td>
                        <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                    </tr>

                    <tr>
                        <td><strong>Género</strong></td>
                        <td>{{ $articulo->DatosArticulo->Genero->nombre }}</td>
                    </tr>

                    <tr>
                        <td><strong>Categoría</strong></td>
                        <td>{{ $articulo->DatosArticulo->Categoria->nombre }}</td>
                    </tr>

                    {{--<tr>
                        <td><strong>Talle</strong></td>
                        <td>{{ $articulo->talle->nombre }}</td>
                    </tr>

                    <tr>
                        <td><strong>Color</strong></td>
                        <td>{{ $articulo->color }}</td>
                    </tr>

                    <tr>
                        <td><strong>Stock Disponible</strong></td>
                        <td>{{ $articulo->cantidad }}</td>
                    </tr>--}}

                    <tr>
                        <td><strong>Precio de Venta</strong></td>
                        <td>${{ number_format($articulo->DatosArticulo->precio, 2) }}</td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend>Modelos</legend>

                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Color</th>
                        <th>Talle</th>
                        <th>Cantidad Disponible</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($articulos_iguales as $articulo_ig)
                        <tr>
                            <td>{{ $articulo_ig->color }}</td>
                            <td>{{ $articulo_ig->Talle->nombre }}</td>
                            <td>{{ $articulo_ig->cantidad }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </fieldset>

            <fieldset>
                <legend>Proveedores</legend>

                @if (count($proveedores) > 0)

                    <table class="table table-bordered table-hover" id="proveedores" style="width:100%">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad Comprada</th>
                            <th>Costo de Compra</th>
                            <th>Fecha de Compra</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($proveedores as $proveedor)
                            <tr>
                                <td>
                                    <strong>{{ $proveedor->nombre }}</strong>
                                </td>
                                <td>{{ $proveedor->pivot->cantidad }}</td>
                                <td>${{ number_format($proveedor->pivot->costo, 2) }}</td>
                                <td>{{ date("d/m/Y", strtotime($proveedor->pivot->created_at)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    No se han cargado proveedores para el articulo
                @endif
            </fieldset>

            <div class="box-footer">
                <div class="pull-xs-left col-xs-6">
                    <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                    </a>
                </div>

                <div class="col-xs-6">
                    <a href="/articulos/{{ $articulo->id }}/edit" class="btn btn-default btn-primary" style="float:right; color: white;">
                        <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/articulos/show.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

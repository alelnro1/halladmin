@extends('layouts.app')

@section('site-name', 'Viendo a proveedor')

@section('page-header', 'Proveedor')
@section('page-description', $proveedor->nombre)

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Proveedores</a></li>
    <li class="active">{{ $proveedor->nombre }}</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Proveedor <b><i>{{ $proveedor->nombre }}</i></b>
        </div>

        <div class="panel-body">
            <fieldset>
                <legend>Datos del Proveedor</legend>

                @if(isset($proveedor->archivo) && $proveedor->archivo != "")
                    <div class="text-center margin-bottom">
                        <img src="/{{ $proveedor->archivo }}" height="250" />
                    </div>
                @endif

                <table class="table table-striped task-table">
                    <tr>
                        <td><strong>Nombre</strong></td>
                        <td>{{ $proveedor->nombre }}</td>
                    </tr>

                    <tr>
                        <td><strong>Descripción</strong></td>
                        <td>{{ $proveedor->descripcion }}</td>
                    </tr>

                    <tr>
                        <td><strong>Domicilio</strong></td>
                        <td>{{ $proveedor->domicilio }}</td>
                    </tr>

                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $proveedor->email }}</td>
                    </tr>

                    <tr>
                        <td><strong>Teléfono</strong></td>
                        <td>{{ $proveedor->telefono }}</td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend>Artículos Proveídos</legend>

                @if (count($articulos) > 0)
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Local</th>
                            <th>Artículo</th>
                            <th>Cantidad Proveída</th>
                            <th>Costo de Compra</th>
                            <th>Precio de Venta</th>
                            <th>Fecha de Compra</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($articulos as $articulo)
                            <tr>
                                <td>
                                    <a href="{{ url('locales/' . $articulo->Local->id) }}">{{ $articulo->Local->nombre }}</a>
                                </td>
                                <td>
                                    <a href="{{ url('articulos/' . $articulo->id) }}">
                                        {{ $articulo->DatosArticulo->descripcion }}
                                    </a>
                                </td>
                                <td>
                                    {{ $articulo->pivot->cantidad }}
                                </td>
                                <td>
                                    ${{ number_format($articulo->pivot->costo, 2) }}
                                </td>
                                <td>
                                    ${{ number_format($articulo->DatosArticulo->precio, 2) }}
                                </td>
                                <td>
                                    {{ date("d/m/Y", strtotime($articulo->pivot->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    El proveedor no ha ingresado artículos
                @endif
            </fieldset>
        </div>

        <div class="box-footer">
            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>

            <div class="col-xs-6">
                <a href="{{ url('/proveedores/' . $proveedor->id . '/edit') }}" class="btn btn-default btn-primary" style="float:right; color: white;">
                    <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
                </a>
            </div>
        </div>
    </div>
@stop

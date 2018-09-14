@extends('layouts.app')

@section('site-name', 'Viendo a articulo')

@section('page-description', 'Artículo')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Artículos</a></li>
    <li class="active">{{ $articulo->DatosArticulo->descripcion }}</li>
@stop

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
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
                    <img src="/{{ $articulo->archivo }}" height="250"/>
                </div>
            @endif

            @include('articulos.articulo.datos')

            @include('articulos.articulo.modelos')

            @include('articulos.articulo.proveedores')

            <div class="box-footer">
                <div class="pull-xs-left col-xs-6">
                    <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                    </a>
                </div>

                <div class="col-xs-6">
                    <a href="{{ route('articulos.edit', ['articulo' => $articulo->id]) }}"
                       class="btn btn-default btn-primary" style="float:right; color: white;">
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
    <script type="text/javascript"
            src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

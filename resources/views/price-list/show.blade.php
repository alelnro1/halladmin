@extends('layouts.app')

@section('site-name', 'Viendo al price list')

@section('page-header', 'Listas de precios')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Lista de Precios</a></li>
    <li class="active">{{ $price_list->getNombre() }}</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><b><i>{{ $price_list->getNombre() }}</i></b></h3>
        </div>

        @include()

        <div class="box-body">
            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>

            <div class="col-xs-6">
            </div>
        </div>
    </div>
@stop

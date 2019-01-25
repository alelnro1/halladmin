@extends('layouts.app')

@section('page-header', 'Local')
@section('page-description', 'Nuevo')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/floating-label.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Clientes</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <fieldset>
                <div class="callout callout-info" role="alert">
                    Ingrese los datos del cliente
                </div>
            </fieldset>

            <div class="col-xs-6">
                @include('clientes.nuevo-cliente-form')
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
@stop
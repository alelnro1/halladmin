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

            <form action="{{ url('clientes') }}" id="crear-cliente" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-4">
                            @include('clientes.form-inputs.nombre')
                        </div>
                        <div class="col-xs-4">
                            @include('clientes.form-inputs.apellido')
                        </div>
                        <div class="col-xs-4">
                            @include('clientes.form-inputs.email')
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-4">
                            @include('clientes.form-inputs.domicilio')
                        </div>
                        <div class="col-xs-4">
                            @include('clientes.form-inputs.telefono')
                        </div>
                        <div class="col-xs-4">
                            @include('clientes.form-inputs.cuit')
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        @include('afip.datos-contribuyente')
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-5"></div>
                        <div class="col-xs-7">
                            <input type="submit" class="btn btn-primary" value="Crear cliente">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('js/buscar-contribuyente.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('form#crear-cliente').submit(function () {
                alert('aca');

            });
            $('body')
                .on('click', '#buscar-contribuyente', function (e) {
                    e.preventDefault();

                    BuscarContribuyente();
                })
        });
    </script>
@stop
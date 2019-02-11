@extends('layouts.app')

@section('page-header', 'Local')
@section('page-description', 'Nuevo')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/floating-label.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Listas de Precios</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="content">
        <form action="{{ url('lista-precios') }}" method="POST">
            @csrf
            <div class="row">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Crear lista de precios</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-xs-3">
                            <fieldset>
                                <legend>Datos Básicos</legend>

                                <!-- Nombre --->
                                @include('price-list.form-inputs.nombre')

                                <!-- Descripcion --->
                                @include('price-list.form-inputs.descripcion')
                            </fieldset>
                        </div>

                        <div class="col-xs-3">

                            <fieldset>
                                <legend>Vigencia</legend>

                                <div class="help-block">Seleccione el rango de fechas en el que la lista estará activa</div>

                                @include('price-list.form-inputs.vigencia-desde')
                                <br>

                                @include('price-list.form-inputs.vigencia-hasta')
                            </fieldset>
                        </div>

                        <div class="col-xs-3">
                            <fieldset>
                                <legend>Días</legend>

                                <div class="help-block">Seleccione los días que desea que la lista esté activa</div>

                                @include('price-list.form-inputs.dias')
                            </fieldset>
                        </div>

                        <div class="col-xs-3">
                            <fieldset>
                                <legend>Opciones</legend>

                                <!-- Activa -->
                                @include('price-list.form-inputs.activa')

                                <!-- Negocio -->
                                @include('price-list.form-inputs.negocio')
                            </fieldset>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-7 col-xs-offset-5">
                                    <input type="submit" class="btn btn-primary" value="Crear">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@stop

@section('javascript')
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datetimepicker({
                //format: 'dd/mm/yyyy',
            });
        });
    </script>
@stop
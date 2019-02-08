@extends('layouts.app')

@section('page-header', 'Local')
@section('page-description', 'Nuevo')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/floating-label.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Clientes</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="content">
        <form action="{{ url('clientes') }}" id="crear-cliente" method="POST">

            <div class="row">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Crear lista de precios</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-xs-5">
                            <fieldset>
                                <legend>Datos Básicos</legend>

                                <!-- Nombre --->
                                <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                                    <label for="nombre">Nombre</label>

                                    <input type="text"
                                           class="form-control"
                                           name="nombre"
                                           value="{{ old('nombre') }}"
                                           placeholder="Escriba el nombre">

                                    @if ($errors->has('nombre'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('nombre') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <!-- Descripcion --->
                                <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                                    <label for="descripcion">Descripcion</label>

                                    <textarea
                                            maxlength="150"
                                            class="form-control"
                                            name="descripcion"
                                            placeholder="Escriba la descripción"
                                    ></textarea>

                                    @if ($errors->has('descripcion'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descripcion') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-xs-3">

                            <fieldset>
                                <legend>Vigencia</legend>

                                <div class="input-group date" data-provide="datepicker">
                                    <span class="input-group-addon" id="basic-addon1">Desde</span>
                                    <input type="text" class="form-control">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                                <br>

                                <div class="input-group date" data-provide="datepicker">
                                    <span class="input-group-addon" id="basic-addon1">Hasta</span>
                                    <input type="text" class="form-control">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </fieldset>

                            <br>

                            <fieldset>
                                <legend>Activo</legend>

                                <div class="form-group {{ $errors->has('activo') ? ' has-error' : '' }}">
                                    <input type="checkbox"
                                           name="activo"
                                           checked/> Marque si desea que la lista este <strong>activa</strong>

                                    @if ($errors->has('activo'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('activo') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-xs-3">
                            <fieldset>
                                <legend>Días</legend>

                                <div class="help-block">Seleccione los días que desea que la lista esté activa</div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Lunes
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Martes
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Miércoles
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Jueves
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Viernes
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Sábado
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <label>
                                            <input type="checkbox" name="dias[1]"> Domingo
                                        </label>
                                    </div>
                                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'mm/dd/yyyy',
                startDate: '-3d'
            });
        });
    </script>
@stop
@extends('layouts.app')

@section('site-name', 'Nuevo administrador')

@section('page-header', 'Administradores')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Administradores</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="box box-primary">

        <div class="panel-heading">Nuevo</div>

        <div class="panel-body">
            <form action="{{ route('administradores.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <fieldset>
                    <legend>Datos Personales</legend>

                    <!-- Nombre -->
                    <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Nombre</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}"  placeholder="Escriba el nombre">

                            @if ($errors->has('nombre'))
                                <span class="help-block">
                                <strong>{{ $errors->first('nombre') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="form-group{{ $errors->has('apellido') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Apellido</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="apellido" value="{{ old('apellido') }}" placeholder="Escriba el apellido">

                            @if ($errors->has('apellido'))
                                <span class="help-block">
                            <strong>{{ $errors->first('apellido') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>

                    <!-- Telefono -->
                    <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Teléfono</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="telefono" value="{{ old('telefono') }}" placeholder="Escriba el teléfono">

                            @if ($errors->has('telefono'))
                                <span class="help-block">
                                <strong>{{ $errors->first('telefono') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Domicilio -->
                    <div class="form-group{{ $errors->has('domicilio') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Domicilio</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="domicilio" value="{{ old('domicilio') }}" id="domicilio" autocomplete="off" placeholder="Escriba la dirección">

                            @if ($errors->has('domicilio'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('domicilio') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Datos de Usuario</legend>

                    <!-- Email -->
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Email</label>

                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Escriba el email">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Contraseña</label>

                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" placeholder="Escriba la contraseña">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Confirmar Contraseña</label>

                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Repita la contraseña">

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </fieldset>


                <fieldset>
                    <legend>Negocio</legend>

                    <div class="help-block">
                        El negocio será la entidad que contendrá a todos los locales creados por el usuario en cuestión.<br>
                        Ej. <strong>Negocio</strong> <i>Kevingston</i>. Todos los locales creados por el administrador, serán del negocio
                        <i>Kevingston</i>, por lo que los clientes compartirán el mismo saldo en cada local de <i>Kevingston</i>
                    </div>

                    <!-- Negocio -->
                    <div class="form-group{{ $errors->has('negocio') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Negocio</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="negocio" value="{{ old('negocio') }}" placeholder="Escriba el negocio">

                            @if ($errors->has('negocio'))
                                <span class="help-block">
                            <strong>{{ $errors->first('negocio') }}</strong>
                        </span>
                            @endif
                        </div>
                    </div>
                </fieldset>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-plus"></i>&nbsp;Crear
                        </button>
                    </div>
                </div>
            </form>

            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>
        </div>

    </div>
@stop

@section('javascript')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAjTpj9h5ANX5iTQIKxkAhI-zcoPxl8GtY"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/administradores/create.js') }}"></script>
@stop

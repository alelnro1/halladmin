@extends('layouts.app')

@section('site-name', 'Editar administrador')

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Administrador</div>

        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ url('administradores/' . $administrador->id) }}" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {!! csrf_field() !!}

                <!-- Nombre -->
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Nombre</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nombre" value="{{ $administrador->nombre }}">

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
                        <input type="text" class="form-control" name="apellido" value="{{ $administrador->apellido }}">

                        @if ($errors->has('apellido'))
                            <span class="help-block">
                            <strong>{{ $errors->first('apellido') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        <input type="email" class="form-control" name="email" value="{{ $administrador->email }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Telefono -->
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Teléfono</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="telefono" value="{{ $administrador->telefono }}">

                        @if ($errors->has('telefono'))
                            <span class="help-block">
                                <strong>{{ $errors->first('telefono') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Archivo -->
                <div class="form-group {{ $errors->has('archivo') ? ' has-error' : '' }}">
                    <label for="archivo" class="control-label col-md-4">Foto</label>

                    <div class="col-md-6">
                        <input type="file" class="form-control" name="archivo">

                        @if ($errors->has('archivo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('archivo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Contraseña</label>

                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password">

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
                        <input type="password" class="form-control" name="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Domicilio -->
                <div class="form-group{{ $errors->has('domicilio') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Domicilio</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="domicilio" value="{{ $administrador->domicilio }}" id="domicilio" autocomplete="off">

                        @if ($errors->has('domicilio'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('domicilio') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-save"></i>&nbsp;Actualizar
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
    <script type="text/javascript" src="{{ asset('/js/administradores/edit.js') }}"></script>
@stop

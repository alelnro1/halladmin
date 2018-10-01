@extends('layouts.app')

@section('page-header', 'Mi Perfil')
@section('page-description', 'Editar')

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Editar Perfil
        </div>

        <div class="panel-body">
            <fieldset>
                <form class="form-horizontal" method="POST" action="{{ route('perfil.procesar-modificacion') }}"
                      enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    @if($usuario->archivo != "")
                        <div class="form-group text-center">
                            <img src="../{{ $usuario->archivo }}" width="250">
                        </div>
                    @endif

                    <!-- Nombre -->
                    <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Nombre</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="nombre" value="{{ $usuario->nombre }}">

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
                            <input type="text" class="form-control" name="apellido" value="{{ $usuario->apellido }}">

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
                            <input type="text" class="form-control" name="email" value="{{ $usuario->email }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Telefono -->
                    <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label">Telefono</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="telefono" value="{{ $usuario->telefono }}">

                            @if ($errors->has('telefono'))
                                <span class="help-block">
                                <strong>{{ $errors->first('telefono') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div class="form-group {{ $errors->has('archivo') ? ' has-error' : '' }}">
                        <label for="archivo" class="control-label col-md-4">Archivo</label>

                        <div class="col-md-6">
                            <input type="file" class="form-control" name="archivo">

                            @if ($errors->has('archivo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('archivo') }}</strong>
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
            </fieldset>

            <fieldset>
                <legend>Cambiar Contraseña</legend>

                @include('auth.cambiar-clave')
            </fieldset>

            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>
        </div>
    </div>
@stop


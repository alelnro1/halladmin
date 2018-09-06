@extends('layouts.app')

@section('site-name', 'Usuarios')

@section('page-description', 'Usuarios')


@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Usuarios</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Nuevo Usuario</div>

        <div class="panel-body">
        <form action="{{ url('usuarios') }}" method="POST" class="form-horizontal" enctype="multipart/form-data" id="form-nuevo-usuario">
            {!! csrf_field() !!}

            <fieldset>
                <legend>Datos Personales</legend>

                <!-- Nombre -->
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Nombre</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre') }}"  placeholder="Escriba el nombre"
                               required data-msg="Este campo es obligatorio.">

                        @if ($errors->has('nombre'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nombre') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Apellido -->
                <div class="form-group {{ $errors->has('apellido') ? ' has-error' : '' }}">
                    <label for="apellido" class="control-label col-md-4">Apellido</label>

                    <div class="col-md-6">
                        <input name="apellido" class="form-control col-md-6" placeholder="Escriba el apellido" id="apellido" value="{{ old('apellido') }}"
                               required data-msg="Este campo es obligatorio.">

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
                        <input type="text" class="form-control" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="Escriba el teléfono">

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
                        <input type="file" class="form-control" name="archivo" id="archivo">

                        @if ($errors->has('archivo'))
                            <span class="help-block">
                            <strong>{{ $errors->first('archivo') }}</strong>
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

                <!-- Módulos Habilitados -->
                <div class="form-group{{ $errors->has('menus') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Módulos Habilitados&nbsp;</label>

                    <div class="col-md-6">
                        <select name="menus[]" id="menus-habilitados" class="form-control show-menu-arrow" multiple="multiple" data-width="90%"
                                title="Seleccione al menos un módulo"
                                data-tickIcon="lala"
                                data-selected-text-format="count > 3">
                            @foreach ($menus as $menu)
                                @if (count($menu->MenusHijos) > 0)
                                    <optgroup label="{{ ucfirst($menu->nombre) }}" class="single">
                                        @foreach ($menu->MenusHijos as $menu_hijo)
                                            <option value="{{ $menu_hijo->id }}">{{ ucfirst($menu_hijo->nombre) }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $menu->id }}">{{ ucfirst($menu->nombre) }}</option>
                                @endif
                            @endforeach
                        </select>
                        &nbsp;
                        <i class="fa fa-question-circle" aria-hidden="true" id="tooltip-modulos-habilitados"
                           title='Los módulos que seleccione son los que podrá ver el usuario.
                       Ej: Si decide que el usuario en cuestión puede ver el item "Mercadería", podrá ingresar mercadería y realizar
                       todas las acciones disponibles dentro de esa sección.
                        '></i>

                        <div class="help-block">
                            Tilde los módulos que desea que el usuario pueda ver.
                        </div>

                        @if ($errors->has('menus'))
                            <span class="help-block">
                                <strong>{{ $errors->first('menus') }}</strong>
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
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Escriba el email"
                               required data-msg="Este campo es obligatorio.">

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
                        <input type="password" class="form-control" name="password" id="password" placeholder="Escriba la contraseña"
                               required data-msg="Este campo es obligatorio.">

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
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Repita la contraseña"
                               required data-msg="Este campo es obligatorio.">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </fieldset>

            <hr>

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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.min.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAjTpj9h5ANX5iTQIKxkAhI-zcoPxl8GtY"></script>
    <script type="text/javascript" src="{{ asset('/js/usuarios/create.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.js') }}"></script>
@stop

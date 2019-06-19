@extends('layouts.app')

@section('site-name', 'Empleados')

@section('page-description', 'Editando a Empleado ' . $usuario->nombre)

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Empleados</a></li>
    <li>{{ $usuario->nombre }}</li>
    <li class="active">Editar</li>
@stop

@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Empleado</div>

        <div class="panel-body">
        <form class="form-horizontal" method="POST"
              action="{{ route('empleados.update', ['usuario' => $usuario->id]) }}"
              enctype="multipart/form-data" id="form-editar-usuario">
            @csrf

            <fieldset>
                <legend>Datos Personales</legend>

                <!-- Nombre -->
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Nombre</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nombre" id="nombre" value="{{ $usuario->nombre }}"  placeholder="Escriba el nombre"
                               required data-msg="Este campo es obligatorio.">

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
                        <input type="text" class="form-control" name="apellido" value="{{ $usuario->apellido }}"
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
                        <input type="text" class="form-control" name="telefono" value="{{ $usuario->telefono }}">

                        @if ($errors->has('telefono'))
                            <span class="help-block">
                            <strong>{{ $errors->first('telefono') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Archivo -->
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

                <!-- Domicilio -->
                <div class="form-group{{ $errors->has('domicilio') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Domicilio</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="domicilio" value="{{ $usuario->domicilio }}" id="domicilio" autocomplete="off">

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
                                data-selected-text-format="count > 3">
                            @foreach ($menus as $menu)
                                @if (count($menu->MenusHijos) > 0)
                                    <optgroup label="{{ ucfirst($menu->nombre) }}" class="single">
                                        @foreach ($menu->MenusHijos as $menu_hijo)
                                            <option value="{{ $menu_hijo->id }}"
                                                    @if ($usuario->Menus->contains($menu_hijo)) selected @endif
                                            >{{ ucfirst($menu_hijo->nombre) }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $menu->id }}"
                                            @if ($usuario->Menus->contains($menu->id)) selected @endif
                                    >{{ ucfirst($menu->nombre) }}</option>
                                @endif
                            @endforeach
                        </select>
                        &nbsp;
                        <i class="fa fa-question-circle" aria-hidden="true" id="tooltip-modulos-habilitados"
                           title='Los módulos que seleccione son las que podrá ver el empleado.
                       Ej: Si decide que el empleado en cuestión puede ver el item "Mercadería", podrá ingresar mercadería y realizar
                       todas las acciones disponibles dentro de esa sección.
                        '></i>

                        <div class="help-block">
                            Tilde los módulos que desea que el empleado pueda ver.
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
                <legend>Datos de Empleado</legend>

                <!-- Email -->
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        <input type="email" class="form-control" name="email" value="{{ $usuario->email }}">

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

            </fieldset>

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
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/usuarios/edit.js') }}"></script>
@stop

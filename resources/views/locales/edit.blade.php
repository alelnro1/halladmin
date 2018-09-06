@extends('layouts.app')

@section('site-name', 'Locales')

@section('page-header', 'Locales')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Locales</a></li>
    <li>{{ $local->nombre }}</li>
    <li class="active">Editar</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><b><i>{{ $local->nombre }}</i></b></h3>
            <small>Editar</small>
        </div>

        <div class="box-body">
        <form class="form-horizontal" method="POST" action="{{ url('locales/' . $local->id) }}" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            {!! csrf_field() !!}

            <fieldset>
                <legend>Datos</legend>

                <!-- Nombre -->
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Nombre</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nombre" value="{{ $local->nombre }}">

                        @if ($errors->has('nombre'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nombre') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Email</label>

                    <div class="col-md-6">
                        <input type="email" class="form-control" name="email" value="{{ $local->email }}">

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
                        <input type="text" class="form-control" name="telefono" value="{{ $local->telefono }}">

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
                        <input type="text" class="form-control" name="domicilio" value="{{ $local->domicilio }}" id="domicilio" autocomplete="off">

                        @if ($errors->has('domicilio'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('domicilio') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>

            </fieldset>

            {{--<fieldset>
                <legend>Categorías</legend>

                <div class="help-block">
                    Seleccione las categorías de ropa que el local en cuestión administrará. Dentro de cada categoría se encuentran los talles predefinidos.
                    Las categorías que ya aparecen marcadas son las que tiene asignadas el local. En caso de que no encuentre el talle deseado, contáctenos.
                </div>

                <!-- Categorías Habilitadas -->
                <div class="form-group {{ $errors->has('categorias') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Categorías Habilitadas&nbsp;</label>

                    <div class="col-md-6">
                        <select name="categorias[]" id="categorias-habilitadas" class="form-control show-menu-arrow" multiple="multiple" data-width="90%"
                                title="Seleccione al menos una categoría"
                                data-selected-text-format="count > 5">
                            @foreach ($categorias as $categoria)
                                @if (count($categoria->CategoriasHijas) > 0)
                                    <optgroup label="{{ ucfirst($categoria->nombre) }}" class="single">
                                        @foreach ($categoria->CategoriasHijas as $categoria_hija)
                                            <option value="{{ $categoria_hija->id }}"
                                                    title="{{ ucfirst($categoria->nombre) }} ({{ ucfirst($categoria_hija->nombre) }})"
                                                    @if ($local->Categorias->contains($categoria_hija)) selected @endif>
                                                {{ ucfirst($categoria_hija->nombre) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $categoria->id }}">{{ ucfirst($categoria->nombre) }}</option>
                                @endif
                            @endforeach
                        </select>
                        &nbsp;
                        <i class="fa fa-question-circle" aria-hidden="true" id="tooltip-modulos-habilitados"
                           title='Los módulos que seleccione son las que podrá ver el usuario.
                                   Ej: Si decide que el usuario en cuestión puede ver el item "Mercadería", podrá ingresar mercadería y realizar
                                   todas las acciones disponibles dentro de esa sección.
                                    '></i>

                        <div class="help-block">
                            Tilde las categorías de ropa que el local administrará.
                        </div>

                        @if ($errors->has('categorias'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('categorias') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
            </fieldset>--}}


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
    <script type="text/javascript" src="{{ asset('/js/locales/edit.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
@stop
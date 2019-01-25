@extends('layouts.app')

@section('site-name', 'Nueva Categoría')

@section('page-header', 'Categorías')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Categorías</a></li>
    <li class="active">Nueva</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Nueva</h3>
        </div>

        <div class="box-body">
            <form action="{{ url('categorias') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf

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

                <!-- Tiene categoría padre -->
                <div class="form-group {{ $errors->has('tiene_padre') ? ' has-error' : '' }}">
                    <label class="control-label col-md-4" for="tiene_padre"></label>

                    <div class="col-md-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="tiene_padre" id="tiene_padre" @if(old('tiene_padre') == "on") checked @endif>
                                Pertenece a una categoría
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Categoria Padre -->
                <div class="form-group{{ $errors->has('padre_id') ? ' has-error' : '' }}" id="padre_id">
                    <label class="col-md-4 control-label">Categoría padre</label>

                    <div class="col-md-6">
                        <select name="padre_id" id="padre_id" class="form-control">
                            <option value="0">Seleccione una categoría padre...</option>

                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>

                        @if ($errors->has('padre_id'))
                            <span class="help-block">
                            <strong>{{ $errors->first('padre_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

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
    <script type="text/javascript" src="{{ asset('js/categorias/categoria_padre.js') }}"></script>
@stop

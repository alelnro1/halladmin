@extends('layouts.app')

@section('site-name', 'Nuevo talle')

@section('page-header', 'Talles')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Talles</a></li>
    <li class="active">Nuevo</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Nuevo</div>

        <div class="panel-body">
        <form action="{{ url('talles') }}" method="POST" class="form-horizontal">
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

            <!-- Categorias -->
            <div class="form-group{{ $errors->has('categoria_id') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Categoría</label>

                <div class="col-md-6">
                    @include('commons.select-categoria', array('page' => 'create'))
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
    <script type="text/javascript" src="{{ asset('/js/talles/create.js') }}"></script>
@stop

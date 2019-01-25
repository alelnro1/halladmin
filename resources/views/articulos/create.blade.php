@extends('layouts.app')

@section('site-name', 'Nuevo articulo')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Nuevo</div>

        <div class="panel-body">
            <form action="{{ url('ABM-PLURAL') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf

                <!-- Codigo -->
                <div class="form-group{{ $errors->has('codigo') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">Código</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control" name="codigo" value="{{ old('codigo') }}"  placeholder="Escriba el código">

                        @if ($errors->has('codigo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('codigo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Descripcion -->
                <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                    <label for="descripcion" class="control-label col-md-4">Descripción</label>

                    <div class="col-md-6">
                        <textarea name="descripcion" rows="3" class="form-control col-md-6" placeholder="Escriba la descripción">{{ old('descripcion') }}</textarea>

                        @if ($errors->has('descripcion'))
                            <span class="help-block">
                                <strong>{{ $errors->first('descripcion') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tipo de Talle -->
                <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
                    <label for="descripcion" class="control-label col-md-4">Tipo de Talle</label>

                    <div class="col-md-6">
                        <textarea name="descripcion" rows="3" class="form-control col-md-6" placeholder="Escriba la descripción">{{ old('descripcion') }}</textarea>

                        @if ($errors->has('descripcion'))
                            <span class="help-block">
                            <strong>{{ $errors->first('descripcion') }}</strong>
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
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAjTpj9h5ANX5iTQIKxkAhI-zcoPxl8GtY"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/articulos/create.js') }}"></script>
@stop

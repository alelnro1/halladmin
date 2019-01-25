@extends('layouts.app')

@section('site-name', 'Nuevo oferta')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Nuevo</div>

        <div class="panel-body">
        <form action="{{ url('ABM-PLURAL') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
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

            <!-- Telefono -->
            <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Telefono</label>

                <div class="col-md-6">
                    <input type="text" class="form-control" name="telefono" value="{{ old('telefono') }}" placeholder="Escriba el teléfono">

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

            <!-- Estado -->
            <div class="form-group {{ $errors->has('estado') ? ' has-error' : '' }}">
                <label class="control-label col-md-4" for="estado">Estado</label>

                <div class="col-md-6">
                    <input type="checkbox" name="estado"> Estado
                </div>
                <div style="clear:both;"></div><br>
            </div>

            <!-- Fecha -->
            <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
              <label for="fecha" class="control-label col-md-4">Fecha</label>

              <div class="col-md-6">
                  <div class="input-group date" id="datetimepicker1">
                      <input type="text" class="form-control" name="fecha" value="{{ old('fecha') }}" autocomplete="off" readonly />
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                  </div>
              </div>
              <div style="clear:both;"></div><br>
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
                    <input type="text" class="form-control" name="domicilio" value="{{ old('domicilio') }}" id="domicilio" autocomplete="off" placeholder="Escriba la dirección">

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
    <script type="text/javascript" src="{{ asset('/js/ofertas/create.js') }}"></script>
@stop

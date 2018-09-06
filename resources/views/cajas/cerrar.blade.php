@extends('layouts.app')

@section('page-header', 'Cierre de Caja')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/floating-label.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Caja</a></li>
    <li class="active">Cierre</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            @if (Session::has('caja_debe_cerrarse'))
                <div class="alert callout callout-danger no-print">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    Para salir del sistema debe cerrar la caja. Por favor hágalo a continuación.
                </div>
            @elseif (Session::has('caja_abierta'))
                <div class="alert callout callout-danger no-print">
                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                    Usted ya tiene una caja abierta. Debe cerrarla para poder reiniciar.
                </div>
            @endif

            <form action="{{ url('caja/cerrar') }}" method="POST" id="cerrar-caja" class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">
                    {!! csrf_field() !!}

                    <fieldset>
                            <span class="help-block">
                                Está a punto de cerrar la caja. Al cerrarla, saldrá del sistema. Ingrese el dinero actual en caja
                            </span>

                        <div class="form-group {{ $errors->has('monto') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Monto Actual</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" name="monto" id="monto"
                                           value="{{ old('monto') }}" placeholder="Ingrese el dinero actual en caja">
                                </div>

                                @if ($errors->has('monto'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('monto') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    <div class="box-footer">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-times"></i>&nbsp;Cerrar Caja
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{--<div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>--}}
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(function(){
            $('#cerrar-caja').on('submit', function (e) {
                monto = $('#monto').val();

                if (monto == "") {
                    alert('Ingrese un monto');
                    e.preventDefault();
                } else if (!confirm('El monto que ha ingresado es $' + monto + '. Es correcto?')) {
                    e.preventDefault();
                }
            })
        });
    </script>
@stop
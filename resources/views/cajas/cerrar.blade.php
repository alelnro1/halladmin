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

            <form action="{{ route('caja.procesar-cierre') }}" method="POST" id="cerrar-caja-form"
                  class="form-horizontal">
                <div class="box-body">
                    {!! csrf_field() !!}

                    <fieldset>
                        <div class="callout callout-info">
                            Está a punto de cerrar la caja. Al cerrarla, saldrá del sistema. Ingrese el dinero actual en caja
                        </div>

                        <div class="form-group {{ $errors->has('monto') ? ' has-error' : '' }}">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-xs-4">
                                        <div class="col-xs-3">
                                            <img src="{{ asset('img/credit/visa.png') }}" style="width: 100%; margin-top: 10px;" alt="">
                                        </div>
                                        <div class="col-xs-5" style="margin-top: 10px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" class="form-control" name="monto" id="monto"
                                                       value="{{ old('monto') }}" placeholder="Ingrese el monto..">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-3">
                                            <img src="{{ asset('img/credit/mastercard.png') }}" style="width: 100%" alt="">
                                        </div>
                                        <div class="col-xs-5" style="margin-top: 10px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" class="form-control" name="monto" id="monto"
                                                       value="{{ old('monto') }}" placeholder="Ingrese el monto..">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-3">
                                            <img src="{{ asset('img/credit/american-express.png') }}" style="width: 100%" alt="">
                                        </div>
                                        <div class="col-xs-5" style="margin-top: 10px;">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="text" class="form-control" name="monto" id="monto"
                                                       value="{{ old('monto') }}" placeholder="Ingrese el monto..">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br><br>
                            {{--}}<label class="col-md-4 control-label">Monto Actual</label>

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
                            </div>--}}
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
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('js/caja/cerrar.js') }}"></script>
@stop
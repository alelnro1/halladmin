@extends('layouts.app')

@section('page-header', 'Mi Negocio')

@section('stylesheets')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Mi Negocio</h3>
            <div class="box-tools">
            </div>
        </div>

        <div class="box-body">

            <ul class="nav nav-tabs">
                {{--<li class="active"><a data-toggle="tab" href="#resumen">Resumen</a></li>--}}
                <li class="active"><a data-toggle="tab" href="#configuracion">Configuración</a></li>
            </ul>

            <div class="tab-content">
                {{--<div id="resumen" class="tab-pane fade in active">
                    <br>
                    <div class="callout callout-info">
                        En esta sección podrá encontrar un resumen de todos sus locales
                    </div>
                </div>--}}

                <div id="configuracion" class="tab-pane fade in active">
                    <br>

                    @if (Session::has('actualizado'))
                        <div class="callout callout-success">
                            La configuración del negocio se actualizó.
                        </div>
                    @endif

                    <div class="callout callout-info">
                        Está editando los datos de su negocio <strong>{{ $negocio->getNombre() }}</strong>
                    </div>

                    <form action="{{ route('negocio.actualizar') }}" method="POST" class="form-horizontal"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group {{ $errors->has('condicion_iva') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Condición frente al IVA</label>
                                    <div class="col-md-6">
                                        <label class="radio-inline">
                                            <input type="radio" name="condicion_iva"
                                                   value="responsable_inscripto"
                                                   @if($negocio->esResponsableInscripto()) checked @endif>
                                            Responsable Inscripto
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="condicion_iva"
                                                   value="monotributista"
                                                   @if ($negocio->esMonotributista()) checked @endif>
                                            Monotributista
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('cuit') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">CUIT</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="cuit" value="{{ old('cuit') }}"
                                       placeholder="Escriba el CUIT de su negocio"
                                       data-cuit-registrado="{{ $negocio->cuit }}"
                                       id="cuit"
                                       autocomplete="off">

                                @if ($errors->has('cuit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cuit') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('punto_venta') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Punto de Venta</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="punto_venta" value="{{ old('punto_venta') }}"
                                       placeholder="Escriba el punto de venta del local"
                                       data-cuit-registrado="{{ $local->punto_venta }}"
                                       id="punto_venta"
                                       autocomplete="off">

                                @if ($errors->has('punto_venta'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('punto_venta') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-plus"></i>&nbsp;Actualizar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            const cuitInput = $("#cuit");
            const cuitRegistrado = cuitInput.data('cuit-registrado');

            cuitInput.val(cuitRegistrado).mask("99-99999999-9");
        });
    </script>
@endsection
@extends('layouts.app')

@section('page-header', $LOCAL_NOMBRE)
@section('page-description', 'Ventas')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/floating-label.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Venta</a></li>
    <li class="active">Datos de Cliente</li>
@stop

@section('content')
    <div class="alert callout callout-info">
        <h4><i class="icon fa fa-info"></i> Nota</h4>
        Seleccione el cliente que está operando o cree uno nuevo.
        Si el cliente no lo desea, puede saltear este paso con el botón Saltar que se encuentra
        debajo de todo.
    </div>

    <div class="row">
        <!-- NUEVO CLIENTE -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Nuevo Cliente</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{ url('clientes') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <!-- Nombre -->
                        <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
                            <label for="nombre">Nombre</label>

                            <input type="text"
                                   class="form-control"
                                   name="nombre"
                                   value="{{ old('nombre') }}"
                                   placeholder="Escriba el nombre del cliente">

                            @if ($errors->has('nombre'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Apellido -->
                        <div class="form-group {{ $errors->has('apellido') ? ' has-error' : '' }}">
                            <label for="apellido">Apellido</label>

                            <input type="text"
                                   class="form-control"
                                   name="apellido"
                                   value="{{ old('apellido') }}"
                                   placeholder="Escriba el apellido del cliente">

                            @if ($errors->has('apellido'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('apellido') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Email -->
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email</label>

                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   autocomplete="off"
                                   value="{{ old('email') }}"
                                   placeholder="Escriba el email del cliente">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Telefono -->
                        <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                            <label for="telefono">Teléfono</label>

                            <input type="text"
                                   class="form-control"
                                   name="telefono"
                                   autocomplete="off"
                                   value="{{ old('telefono') }}"
                                   placeholder="Escriba el teléfono del cliente">

                            @if ($errors->has('telefono'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('telefono') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>

        <!-- CLIENTE EXISTENTE -->
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-info" style="padding:5px;">
                <div class="box-header with-border">
                    <h3 class="box-title">Cliente Existente</h3>
                </div>
                <div class="box-body">

                    @if (count($clientes) > 0)
                        <table class="table table-bordered table-hover" id="clientes_existentes" style="width:100%;">
                            <!-- Table Headings -->
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th class="all"></th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th></th>
                            </tr>
                            </tfoot>

                            <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                                    <td>{{ $cliente->email }}</td>
                                    <td>{{ $cliente->telefono }}</td>
                                    <td class="all">
                                        <a href="#" class="seleccionar-cliente" data-id="{{ $cliente->id }}">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        No existen clientes en el negocio
                    @endif
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>

    <div class="box-footer">
        <div class="pull-xs-left col-xs-6">
            <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
            </a>
        </div>

        <div class="text-right">
            <a href="#" id="saltar-eleccion-cliente" class="btn btn-default btn-sm">
                <i class="fa fa-step-forward" aria-hidden="true"></i>
                Saltar
            </a>
        </div>
    </div>
@stop

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('js/clientes/datos-de-venta.js') }}"></script>
@stop
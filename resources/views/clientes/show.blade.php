@extends('layouts.app')

@section('site-name', 'Viendo a local')

@section('page-header', 'Locales')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Clientes</a></li>
    <li class="active">{{ $cliente->getNombre() }}</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><b><i>{{ $cliente->getNombre() }}</i></b></h3>
        </div>

        <div class="box-body">

            <table class="table table-striped task-table" style="margin-bottom: 20px;">
                <tr>
                    <td><strong>Nombre</strong></td>
                    <td>{{ $cliente->getNombreCompleto() }}</td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>{{ $cliente->getEmail() }}</td>
                </tr>

                <tr>
                    <td><strong>Telefono</strong></td>
                    <td>{{ $cliente->getTelefono() }}</td>
                </tr>

                <tr>
                    <td><strong>Domicilio</strong></td>
                    <td>{{ $cliente->getDomicilio() }}</td>
                </tr>

            </table>

            <div class="col-xs-6">
                {{--<a href="{{ route('locales.edit', ['local' => $local->id]) }}" class="btn btn-default btn-primary" style="float:right; color: white;">
                    <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
                </a>--}}
            </div>
        </div>
    </div>

    @include('clientes.compras-cliente')

    <div class="pull-xs-left col-xs-6">
        <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
            <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
        </a>
    </div>
@stop

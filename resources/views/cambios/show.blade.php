@extends('layouts.app')

@section('site-name', 'Viendo a cambio')

@section('content')
    <div class="panel-heading">
        Cambio con nombre <b><i>{{ $cambio->nombre }}</i></b>
    </div>

    <div class="panel-body">

        @if(isset($cambio->archivo) && $cambio->archivo != "")
            <div class="text-center margin-bottom">
                <img src="/{{ $cambio->archivo }}" style="max-height: 150px;" />
            </div>
        @endif

        <table class="table table-striped task-table" style="margin-bottom: 20px;">
            <tr>
                <td><strong>Nombre</strong></td>
                <td>{{ $cambio->nombre }}</td>
            </tr>

            <tr>
                <td><strong>Descripción</strong></td>
                <td>{{ $cambio->descripcion }}</td>
            </tr>

            <tr>
                <td><strong>Estado</strong></td>
                <td>{{ $cambio->estado }}</td>
            </tr>

            <tr>
                <td><strong>Fecha</strong></td>
                <td>{{ date('Y/m/d', strtotime($cambio->fecha)) }}</td>
            </tr>

            <tr>
                <td><strong>Domicilio</strong></td>
                <td>{{ $cambio->domicilio }}</td>
            </tr>

            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $cambio->email }}</td>
            </tr>

            <tr>
                <td><strong>Teléfono</strong></td>
                <td>{{ $cambio->telefono }}</td>
            </tr>

            <tr>
                <td><strong>Fecha Creación</strong></td>
                <td>{{ $cambio->created_at }}</td>
            </tr>
        </table>

        <div class="pull-xs-left col-xs-6">
            <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
            </a>
        </div>

        <div class="col-xs-6">
            <a href="/cambios/{{ $cambio->id }}/edit" class="btn btn-default btn-primary" style="float:right; color: white;">
                <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
            </a>
        </div>
    </div>
@stop

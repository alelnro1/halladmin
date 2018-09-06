@extends('layouts.app')

@section('site-name', 'Viendo a oferta')

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Oferta con nombre <b><i>{{ $oferta->nombre }}</i></b>
        </div>

        <div class="panel-body">

        @if(isset($oferta->archivo) && $oferta->archivo != "")
            <div class="text-center margin-bottom">
                <img src="/{{ $oferta->archivo }}" height="250" />
            </div>
        @endif

        <table class="table table-striped task-table" style="margin-bottom: 20px;">
            <tr>
                <td><strong>Nombre</strong></td>
                <td>{{ $oferta->nombre }}</td>
            </tr>

            <tr>
                <td><strong>Descripción</strong></td>
                <td>{{ $oferta->descripcion }}</td>
            </tr>

            <tr>
                <td><strong>Estado</strong></td>
                <td>{{ $oferta->estado }}</td>
            </tr>

            <tr>
                <td><strong>Fecha</strong></td>
                <td>{{ date('Y/m/d', strtotime($oferta->fecha)) }}</td>
            </tr>

            <tr>
                <td><strong>Domicilio</strong></td>
                <td>{{ $oferta->domicilio }}</td>
            </tr>

            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $oferta->email }}</td>
            </tr>

            <tr>
                <td><strong>Telefono</strong></td>
                <td>{{ $oferta->telefono }}</td>
            </tr>

            <tr>
                <td><strong>Archivo</strong></td>
                <td><img src="/{{ $oferta->archivo }}" height="250" /></td>
            </tr>

            <tr>
                <td><strong>Fecha Creacion</strong></td>
                <td>{{ $oferta->created_at }}</td>
            </tr>
        </table>

        <div class="pull-xs-left col-xs-6">
            <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
            </a>
        </div>

        <div class="col-xs-6">
            <a href="/locales/{{ $local->id }}/edit" class="btn btn-default btn-primary" style="float:right; color: white;">
                <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
            </a>
        </div>
    </div>
    </div>
@stop

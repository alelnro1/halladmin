@extends('layouts.app')

@section('site-name', 'Viendo a talle')

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Talle con nombre <b><i>{{ $talle->nombre }}</i></b>
        </div>

        <div class="panel-body">

        @if(isset($talle->archivo) && $talle->archivo != "")
            <div class="text-center margin-bottom">
                <img src="/{{ $talle->archivo }}" height="250" />
            </div>
        @endif

        <table class="table table-striped task-table" style="margin-bottom: 20px;">
            <tr>
                <td><strong>Nombre</strong></td>
                <td>{{ $talle->nombre }}</td>
            </tr>

            <tr>
                <td><strong>Archivo</strong></td>
                <td><img src="/{{ $talle->archivo }}" height="250" /></td>
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

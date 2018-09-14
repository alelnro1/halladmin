@extends('layouts.app')

@section('site-name', 'Viendo a local')

@section('page-header', 'Locales')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Locales</a></li>
    <li class="active">{{ $local->nombre }}</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><b><i>{{ $local->nombre }}</i></b></h3>
        </div>

        <div class="box-body">
        @if(isset($local->archivo) && $local->archivo != "")
            <div class="text-center margin-bottom">
                <img src="/{{ $local->archivo }}" style="max-height: 150px;" />
            </div>
        @endif

        <table class="table table-striped task-table" style="margin-bottom: 20px;">
            <tr>
                <td><strong>Nombre</strong></td>
                <td>{{ $local->nombre }}</td>
            </tr>

            <tr>
                <td><strong>Email</strong></td>
                <td>{{ $local->email }}</td>
            </tr>

            <tr>
                <td><strong>Telefono</strong></td>
                <td>{{ $local->telefono }}</td>
            </tr>

            <tr>
                <td><strong>Domicilio</strong></td>
                <td>{{ $local->domicilio }}</td>
            </tr>

        </table>

        {{--<fieldset>
            <legend>Categorías Habilitadas</legend>

            <div class="help-block">
                El local tiene habilitadas las siguientes categorías. Si quiere cambiarlas, haga
                <a href="{{ url('locales/' . $local->id . '/edit') }}">click acá</a>
            </div>

            <div class="description-block">
                <table class="table table-striped task-table" style="margin-bottom: 20px; text-align: left;">
                    @foreach ($local->Categorias as $categoria)
                        <tr>
                            <td>
                                {{ ucfirst($categoria->nombre) }}
                                @if ($categoria->padre_id != null)
                                    <span style="font-size: 11px; color: #737373;">
                                        (Pertenece al módulo <u>{{ ucfirst($categoria->CategoriaPadre->nombre) }}</u>)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </fieldset>--}}

        <div class="pull-xs-left col-xs-6">
            <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
            </a>
        </div>

        <div class="col-xs-6">
            <a href="{{ route('locales.edit', ['local' => $local->id]) }}" class="btn btn-default btn-primary" style="float:right; color: white;">
                <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
            </a>
        </div>
    </div>
    </div>
@stop

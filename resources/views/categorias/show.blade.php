@extends('layouts.app')

@section('site-name', 'Viendo categoría')

@section('page-header', 'Categoría')

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Categoría con nombre <b><i>{{ $categoria->nombre }}</i></b>
        </div>


        <div class="panel-body">
            @if(isset($categoria->archivo) && $categoria->archivo != "")
                <div class="text-center margin-bottom">
                    <img src="/{{ $categoria->archivo }}" height="250" />
                </div>
            @endif

            <table class="table table-striped task-table" style="margin-bottom: 20px;">
                <tr>
                    <td><strong>Nombre</strong></td>
                    <td>{{ $categoria->nombre }}</td>
                </tr>

                @if ($categoria->CategoriaPadre)
                    <tr>
                        <td><strong>Padre</strong></td>
                        <td>{{ $categoria->CategoriaPadre->nombre }}</td>
                    </tr>
                @endif
            </table>

            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>

            <div class="col-xs-6">
                <a href="/categorias/{{ $categoria->id }}/edit" class="btn btn-default btn-primary" style="float:right; color: white;">
                    <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
                </a>
            </div>
        </div>
    </div>
@stop

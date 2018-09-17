@extends('layouts.app')

@section('site-name', 'Usuario')

@section('page-description', 'Usuario ' . $usuario->nombre)

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Usuarios</a></li>
    <li class="active">{{ $usuario->nombre }} {{ $usuario->apellido }}</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-body">

            @if(isset($usuario->archivo) && $usuario->archivo != "")
                <div class="text-center margin-bottom">
                    <img src="{{ url('storage/' . $usuario->archivo) }}" width="250">
                </div>
            @endif

            <fieldset>
                <legend>Datos Personales</legend>

                <table class="table table-striped task-table" style="margin-bottom: 20px;">
                    <tr>
                        <td><strong>Nombre</strong></td>
                        <td>{{ $usuario->nombre }}</td>
                    </tr>

                    <tr>
                        <td><strong>Apellido</strong></td>
                        <td>{{ $usuario->apellido }}</td>
                    </tr>

                    <tr>
                        <td><strong>Domicilio</strong></td>
                        <td>{{ $usuario->domicilio }}</td>
                    </tr>

                    <tr>
                        <td><strong>Teléfono</strong></td>
                        <td>{{ $usuario->telefono }}</td>
                    </tr>

                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $usuario->email }}</td>
                    </tr>
                </table>
            </fieldset>


            <fieldset>
                <legend>Ventas Realizadas</legend>

                @if(count($usuario->Ventas) > 0)
                    @include('ventas.listado-ventas', ['ventas' => $usuario->Ventas])
                @else
                    El usuario no ha realizado ventas
                @endif

                <br><br>
            </fieldset>

            @if (!$usuario->esSuperAdmin() && !$usuario->esAdmin())
                <fieldset>
                    <legend>Módulos Habilitados</legend>

                    <div class="help-block">
                        El usuario tiene acceso a los siguientes módulos. Si quiere cambiarlos, haga
                        <a href="{{ route('usuarios.edit', ['usuario' => $usuario->id]) }}">click acá</a>
                    </div>

                    <div class="description-block">
                        <table class="table table-striped task-table" style="margin-bottom: 20px; text-align: left;">
                            @foreach ($usuario->Menus as $menu)
                                <tr>
                                    <td>
                                        {{ ucfirst($menu->nombre) }}
                                        @if ($menu->padre_id != null)
                                            <span style="font-size: 11px; color: #737373;">
                                            (Pertenece al módulo <u>{{ ucfirst($menu->MenuPadre->nombre) }}</u>)
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </fieldset>
            @endif

            <div class="box-footer">
                <div class="pull-xs-left col-xs-6">
                    <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                        <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                    </a>
                </div>

                <div class="col-xs-6">
                    <a href="{{ route('usuarios.edit', ['usuario' => $usuario->id]) }}" class="btn btn-default btn-primary" style="float:right; color: white;">
                        <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar Usuario
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/reportes.js') }}"></script>
@stop
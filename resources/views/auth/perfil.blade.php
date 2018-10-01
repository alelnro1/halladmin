@extends('layouts.app')

@section('site-name', 'Mi Perfil')

@section('page-header', 'Mi Perfil')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Mi Perfil</a></li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-body">
            @if(Session::has('perfil_actualizado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('perfil_actualizado') }}
                </div>
            @endif

            @if($usuario->archivo != "")
                <div class="form-group text-center">
                    <img src="{{ url('storage/' . $usuario->archivo) }}" width="250">
                </div>
            @endif

            <fieldset>

                <legend>Datos</legend>
                    <table class="table table-striped task-table">
                        <tr>
                            <td><strong>Nombre</strong></td>
                            <td>{{ $usuario->nombre }}</td>
                        </tr>

                        <tr>
                            <td><strong>Apellido</strong></td>
                            <td>{{ $usuario->apellido }}</td>
                        </tr>

                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $usuario->email }}</td>
                        </tr>

                        <tr>
                            <td><strong>Teléfono</strong></td>
                            <td>{{ $usuario->telefono }}</td>
                        </tr>

                        <tr>
                            <td><strong>Fecha de alta</strong></td>
                            <td>{{ date("d/m/Y", strtotime($usuario->created_at)) }}</td>
                        </tr>
                    </table>
            </fieldset>

            <div class="col-xs-12">
                <a href="{{ route('perfil.modificar') }}" class="btn btn-default btn-primary" style="float:right; color: white;">
                    <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Modificar
                </a>
            </div>
        </div>
    </div>
@stop


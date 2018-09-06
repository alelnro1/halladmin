@extends('layouts.app')

@section('site-name', 'Administradores')

@section('page-header', 'Administradores')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop


@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Administradores</a></li>
    <li class="active">{{ $administrador->nombre }} {{ $administrador->apellido }}</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Administrador <b><i>{{ $administrador->nombre }} {{ $administrador->apellido }}</i></b>
        </div>

        <div class="panel-body">

            @if(isset($administrador->archivo) && $administrador->archivo != "")
                <div class="text-center margin-bottom">
                    <img src="/{{ $administrador->archivo }}" style="max-height: 150px;" />
                </div>
            @endif

            <fieldset>
                <legend>Datos</legend>

                <table class="table table-striped task-table" style="margin-bottom: 20px;">
                    <tr>
                        <td><strong>Nombre</strong></td>
                        <td>{{ $administrador->nombre }}</td>
                    </tr>

                    <tr>
                        <td><strong>Apellido</strong></td>
                        <td>{{ $administrador->apellido }}</td>
                    </tr>

                    <tr>
                        <td><strong>Fecha de Alta</strong></td>
                        <td>{{ date('d/m/Y', strtotime($administrador->created_at)) }}</td>
                    </tr>

                    <tr>
                        <td><strong>Domicilio</strong></td>
                        <td>{{ $administrador->domicilio }}</td>
                    </tr>

                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $administrador->email }}</td>
                    </tr>

                    <tr>
                        <td><strong>Teléfono</strong></td>
                        <td>{{ $administrador->telefono }}</td>
                    </tr>

                    <tr>
                        <td><strong>Fecha Creación</strong></td>
                        <td>{{ $administrador->created_at }}</td>
                    </tr>
                </table>
            </fieldset>

            <fieldset>
                <legend>Locales</legend>

                <table class="table table-bordered table-hover">
                    <thead>
                    <th>Nombre</th>
                    <th>Artículos</th>
                    <th>Usuarios</th>
                    <th>Ventas</th>
                    <th>Cambios</th>
                    </thead>

                    <tbody>
                    @foreach ($administrador->Locales as $local)
                        <tr>
                            <td>{{ $local->nombre }}</td>
                            <td>{{ $local->articulos_count }}</td>
                            <td>{{ $local->usuarios_count }}</td>
                            <td>{{ $local->ventas_count }}</td>
                            <td>{{ $local->cambios_count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </fieldset>

            <fieldset>
                <legend>Logins</legend>

                <table class="table table-bordered table-hover" id="logins" width="100%">
                    <thead>
                    <th>IP</th>
                    <th>Agente</th>
                    <th>Fecha</th>
                    </thead>

                    <tbody>
                    @foreach ($administrador->Logins as $login)
                        <tr>
                            <td>{{ $login->ip }}</td>
                            <td>{{ $login->agent }}</td>
                            <td>{{ date("d/m/Y H:i", strtotime($login->created_at)) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </fieldset>

            <div class="pull-xs-left col-xs-6">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-default">
                    <i class="fa fa-fw fa-arrow-left"></i>&nbsp;Volver
                </a>
            </div>

            <div class="col-xs-6">
                <a href="/administradores/{{ $administrador->id }}/edit" class="btn btn-default btn-primary" style="float:right; color: white;">
                    <i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Editar
                </a>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(window).load(function() {
            oTable = $('#logins').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                searcheable: false,
                "language": {
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ administradores filtrados",
                    "paginate": {
                        "first":      "Primera",
                        "last":       "Ultima",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                    "lengthMenu": "Mostrar _MENU_ administradores",
                    "search": "Buscar:",
                    "infoFiltered": "(de un total de _MAX_ administradores)",
                }
            });
        });

    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop


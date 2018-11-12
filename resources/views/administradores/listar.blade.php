@extends('layouts.app')

@section('site-name', 'Administradores')

@section('page-header', 'Administradores')
@section('page-description', 'Listado')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Mercadería</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools">
                <a href="{{ route('administradores.create') }}" class="btn btn-block btn-success btn-sm">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Nuevo Administrador
                </a>
            </div>
        </div>

        <div class="panel-body">
            @if(Session::has('administrador_eliminado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('administrador_eliminado') }}
                </div>
            @endif

            @if(Session::has('administrador_creado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('administrador_creado') }}
                </div>
            @endif

            @if(Session::has('administrador_actualizado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('administrador_actualizado') }}
                </div>
            @endif

            @if (count($administradores) > 0)
                <table class="table table-bordered table-hover" id="administradores" style="width:100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Negocio</th>
                        <th>Fecha de Alta</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($administradores as $administrador)
                        <tr>
                            <td>{{ $administrador->nombre }} {{ $administrador->apellido }}</td>
                            <td>{{ $administrador->telefono }}</td>
                            <td>{{ $administrador->email }}</td>
                            <td>
                                @if ($administrador->Negocio)
                                    {{ $administrador->Negocio->nombre }}
                                @endif
                            </td>
                            <td>{{ date('d/m/Y', strtotime($administrador->created_at)) }}</td>

                            <td>
                                <a href="{{ url('administradores/' . $administrador['id']) }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Ver
                                </a>

                                <a href="{{ url('administradores/' . $administrador['id'] . '/edit') }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                    Editar
                                </a>

                                {{--<a href="{{ url('administradores/' . $administrador['id']) }}" class="btn btn-danger btn-sm"
                                   data-method="delete"
                                   data-token="{{ csrf_token() }}"
                                   data-confirm="Está seguro que desea eliminar al administrador {{ $administrador->nombre }}?">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Eliminar
                                </a>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay administradores
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/administradores/listar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

@extends('layouts.app')

@section('site-name', 'Listando usuarios')

@section('page-description', 'Empleados')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Empleados</a></li>
    <li class="active">Listado</li>
@stop


@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Listado</h3>
            <div class="box-tools">
                <a href="{{ route('empleados.create') }}" class="btn btn-block btn-success btn-sm">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Nuevo Empleado
                </a>
            </div>
        </div>

        <div class="panel-body">
            @if(Session::has('usuario_eliminado'))
                <div class="callout callout-success">
                    {{ Session::get('usuario_eliminado') }}
                </div>
            @endif

            @if(Session::has('usuario_creado'))
                <div class="callout callout-success">
                    {{ Session::get('usuario_creado') }}
                </div>
            @endif

            @if(Session::has('usuario_actualizado'))
                <div class="callout callout-success">
                    {{ Session::get('usuario_actualizado') }}
                </div>
            @endif

            @if (count($usuarios) > 0)
                <table class="table table-bordered table-hover responsive table-striped" id="usuarios" width="100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->telefono }}</td>

                            <td>
                                <a href="{{ route('empleados.view', ['usuario' => $usuario['id']]) }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Ver
                                </a>

                                <a href="{{ route('empleados.edit', ['usuario' => $usuario['id']]) }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                    Editar
                                </a>

                                <a href="{{ route('empleados.delete', ['usuario' => $usuario['id']]) }}"
                                   class="btn btn-danger btn-sm eliminar-elem"
                                   data-confirm="Está seguro que desea eliminar al usuario <strong>{{ $usuario->nombre }}?</strong>">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay empleados
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/usuarios/listar.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop
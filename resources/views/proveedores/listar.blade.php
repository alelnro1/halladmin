@extends('layouts.app')

@section('site-name', 'Listando proveedores')

@section('page-header', 'Mis Proveedores')
@section('page-description', 'Listado')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Mis Proveedores</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Listado</h3>
            <div class="box-tools">
                <a href="{{ url('proveedores/create') }}" class="btn btn-block btn-success btn-sm">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Nuevo Proveedor
                </a>
            </div>
        </div>

        <div class="box-body">
        @if(Session::has('proveedor_eliminado'))
            <div class="callout callout-success">
                {{ Session::get('proveedor_eliminado') }}
            </div>
        @endif

        @if(Session::has('proveedor_creado'))
            <div class="callout  callout-success">
                {{ Session::get('proveedor_creado') }}
            </div>
        @endif
        
        @if(Session::has('proveedor_actualizado'))
            <div class="callout callout-success">
                {{ Session::get('proveedor_actualizado') }}
            </div>
        @endif

        @if (count($proveedores) > 0)
            <table class="table table-bordered table-hover" id="proveedores" style="width:100%">
                <!-- Table Headings -->
                <thead>
                    <tr>
                        <th>Nombre</th>
                        {{--<th>Descripción</th>--}}
                        <th>Email</th>
                        <th>Telefono</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($proveedores as $proveedor)
                    <tr>
                        <td>{{ $proveedor->nombre }}</td>
                        {{--<td>{{ $proveedor->descripcion }}</td>--}}
                        <td>{{ $proveedor->email }}</td>
                        <td>{{ $proveedor->telefono }}</td>

                        <td>
                            <a href="{{ url('proveedores/' . $proveedor['id']) }}" class="btn btn-default btn-xs">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                Ver
                            </a>

                            <a href="{{ url('proveedores/' . $proveedor['id'] . '/edit') }}" class="btn btn-default btn-xs">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                Editar
                            </a>

                            <a href="{{ url('/proveedores/' . $proveedor['id']) }}" class="btn btn-danger btn-xs"
                               data-method="delete"
                               data-token="{{ csrf_token() }}"
                               data-confirm="Esta seguro que desea eliminar a proveedor con nombre {{ $proveedor->nombre }}?">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Eliminar
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            No hay proveedores
        @endif
    </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/proveedores/listar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

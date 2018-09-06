@extends('layouts.app')

@section('site-name', 'Listando talles')

@section('page-header', 'Talles')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Talles</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Talles

            <div style="float:right;">
                <a href="/talles/create" class="btn btn-block btn-default btn-sm">Nuevo</a>
            </div>
        </div>
        <div class="panel-body">
        @if(Session::has('talle_eliminado'))
            <div class="alert alert-success">
                {{ Session::get('talle_eliminado') }}
            </div>
        @endif

        @if(Session::has('talle_creado'))
            <div class="alert alert-success">
                {{ Session::get('talle_creado') }}
            </div>
        @endif
        
        @if(Session::has('talle_actualizado'))
            <div class="alert alert-success">
                {{ Session::get('talle_actualizado') }}
            </div>
        @endif

        @if (count($talles) > 0)
            <table class="table table-bordered table-hover" id="talles" style="width:100%">
                <!-- Table Headings -->
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($talles as $talle)
                    <tr>
                        <td>{{ $talle->nombre }}</td>
                        <td>
                            {{ $talle->categoria->CategoriaPadre->nombre }} →
                            {{ $talle->categoria->nombre }}
                        </td>

                        <td>
                            <a href="{{ url('talles/' . $talle['id']) }}/edit" class="btn btn-default btn-sm">Editar</a>
                            
                            <a href="{{ url('talles/' . $talle['id']) }}" class="btn btn-danger btn-sm"
                               data-method="delete"
                               data-token="{{ csrf_token() }}"
                               data-confirm="Esta seguro que desea eliminar a talle con nombre {{ $talle->nombre }}?">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Eliminar
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            No hay talles
        @endif
    </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/talles/listar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

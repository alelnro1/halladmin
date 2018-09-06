@extends('layouts.app')

@section('site-name', 'Categorías')

@section('page-header', 'Categorías')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Categorías</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Categorías

            <div style="float:right;">
                <a href="{{ url('categorias/create') }}" class="btn btn-block btn-default btn-sm">Nueva</a>
            </div>
        </div>
        <div class="panel-body">
            @if(Session::has('categoria_eliminada'))
                <div class="alert alert-success">
                    {{ Session::get('categoria_eliminada') }}
                </div>
            @endif

            @if(Session::has('categoria_creado'))
                <div class="alert alert-success">
                    {{ Session::get('categoria_creado') }}
                </div>
            @endif

            @if(Session::has('categoria_actualizado'))
                <div class="alert alert-success">
                    {{ Session::get('categoria_actualizado') }}
                </div>
            @endif

            @if (count($categorias) > 0)
                <table class="table table-bordered table-hover" id="tiposTalles" style="width:100%">
                    <!-- Table Headings -->
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>

                            <td>
                                <a href="/categorias/{{ $categoria['id'] }}" class="btn btn-default btn-sm">Ver</a>

                                <a href="{{ url('categorias/' . $categoria['id']) }}" class="btn btn-danger btn-sm"
                                   data-method="delete"
                                   data-token="{{ csrf_token() }}"
                                   data-confirm="Esta seguro que desea eliminar la categoría {{ $categoria->nombre }}?">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay categorías creadas
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('categorias') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

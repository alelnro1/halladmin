@extends('layouts.app')

@section('site-name', 'Listando ofertas')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Ofertas

            <div style="float:right;">
                <a href="/ofertas/create" class="btn btn-block btn-default btn-sm">Nuevo</a>
            </div>
        </div>
        <div class="panel-body">
        @if(Session::has('oferta_eliminado'))
            <div class="alert alert-success">
                {{ Session::get('oferta_eliminado') }}
            </div>
        @endif

        @if(Session::has('oferta_creado'))
            <div class="alert alert-success">
                {{ Session::get('oferta_creado') }}
            </div>
        @endif
        
        @if(Session::has('oferta_actualizado'))
            <div class="alert alert-success">
                {{ Session::get('oferta_actualizado') }}
            </div>
        @endif

        @if (count($ofertas) > 0)
            <table class="table table-bordered table-hover" id="ofertas" style="width:100%">
                <!-- Table Headings -->
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($ofertas as $oferta)
                    <tr>
                        <td>{{ $oferta->nombre }}</td>
                        <td style="width:75%">{{ $oferta->descripcion }}</td>
                        <td>{{ $oferta->estado }}</td>
                        <td>{{ date('Y/m/d', strtotime($oferta->fecha)) }}</td>
                        <td>{{ $oferta->email }}</td>
                        <td>{{ $oferta->telefono }}</td>

                        <td>
                            <a href="/ofertas/{{ $oferta['id'] }}" class="btn btn-default btn-sm">Ver</a>
                            
                            <a href="{{ url('ofertas/' . $oferta['id']) }}" class="btn btn-danger btn-sm"
                               data-method="delete"
                               data-token="{{ csrf_token() }}"
                               data-confirm="Esta seguro que desea eliminar a oferta con nombre {{ $oferta->nombre }}?">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                Eliminar
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            No hay ofertas
        @endif
    </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/ofertas/listar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

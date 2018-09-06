@extends('layouts.app')

@section('site-name', 'Listando cambios')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('page-description', 'Cambios')

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Cambios</a></li>
    <li class="active">Nuevo</li>
@stop

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">Nuevo Cambio</div>
        <div class="panel-body">
            @if(Session::has('cambio_eliminado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('cambio_eliminado') }}
                </div>
            @endif

            @if(Session::has('cambio_creado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('cambio_creado') }}
                </div>
            @endif

                <div class="help-block">
                    Seleccione el artículo que se desea devolver
                </div>

            @if (count($articulos) > 0)
                {{ csrf_field() }}
                <table class="table table-bordered table-hover" id="articulos" style="width:100%">
                    <!-- Table Headings -->
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Talle</th>
                            <th>Género</th>
                            <th>Color</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Talle</th>
                            <th>Género</th>
                            <th>Color</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </tfoot>

                    <tbody>
                    @foreach ($articulos as $articulo)
                        <tr>
                            <td>{{ $articulo->DatosArticulo->codigo }}</td>
                            <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                            <td>{{ $articulo->DatosArticulo->Categoria->nombre }}</td>
                            <td>{{ $articulo->talle->nombre }}</td>
                            <td>{{ $articulo->genero }}</td>
                            <td>{{ $articulo->color }}</td>
                            <td class="text-right">${{ number_format($articulo->DatosArticulo->precio, 2) }}</td>
                            <td>
                                <a href="#" data-id="{{ $articulo->id }}" class="btn btn-sm btn-default seleccionar-articulo">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;
                                    Seleccionar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay artículos para cambiar
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/cambios/listar.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

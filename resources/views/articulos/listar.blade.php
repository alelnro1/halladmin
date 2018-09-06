@extends('layouts.app')

@section('site-name', 'Listando articulos')

@section('page-description', 'Mercadería')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
    <style>
        tr.group,
        tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i>&nbsp;Mercadería</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            Articulos

            <div style="float:right;">
                @if (Auth::user()->tieneModuloHabilitado('ingreso'))
                    <a href="{{ url('mercaderia/ingreso') }}" class="btn btn-block btn-success btn-sm">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Nuevo Artículo
                    </a>
                @endif
            </div>
        </div>
        <div class="panel-body">
            @if(Session::has('articulo_eliminado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('articulo_eliminado') }}
                </div>
            @endif

            @if(Session::has('articulo_creado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('articulo_creado') }}
                </div>
            @endif

            @if(Session::has('articulo_actualizado'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('articulo_actualizado') }}
                </div>
            @endif

            @if(Session::has('mercaderia_ingresada'))
                <div class="callout callout-success">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ Session::get('mercaderia_ingresada') }}
                </div>
            @endif

            @if (count($articulos) > 0)
                <table class="table table-bordered table-hover responsive" id="articulos" width="100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Talle</th>
                        <th class="hidden-xs">Color</th>
                        <th class="col-xs-1">Precio</th>
                        <th class="hidden-xs col-xs-1">Stock</th>
                        <th class="col-xs-2"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($articulos as $articulo)
                        <tr>
                            <td>
                                <strong>
                                    <i>{{ $articulo->DatosArticulo->codigo }}</i> |
                                    {{ $articulo->DatosArticulo->descripcion }} |
                                    <span class="hidden-xs">{{ $articulo->DatosArticulo->Categoria->nombre }} |
                                    {{ $articulo->DatosArticulo->Genero->nombre }}</span>
                                </strong>
                            </td>
                            <td>{{ $articulo->Talle->nombre }}</td>
                            <td class="hidden-xs col-xs-1">{{ $articulo->color }}</td>
                            <td class="text-right col-xs-1">${{ number_format($articulo->DatosArticulo->precio, 2) }}</td>
                            <td class="hidden-xs col-xs-1">{{ $articulo->cantidad }}</td>

                            <td class="col-xs-3">
                                <a href="{{ url('articulos/' . $articulo['id']) }}"
                                   class="btn btn-default btn-sm btn-xs">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Ver
                                </a>

                                <a href="{{ url('articulos/' . $articulo['id'] . '/edit') }}"
                                   class="btn btn-default btn-sm btn-xs">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                    Editar
                                </a>

                                <a href="{{ url('articulos/' . $articulo['id']) }}"
                                   class="btn btn-default btn-sm btn-xs"
                                   data-method="delete"
                                   data-token="{{ csrf_token() }}"
                                   data-confirm="Esta seguro que desea eliminar a articulo con nombre {{ $articulo->nombre }}?">
                                    <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                    <span class="text-danger">
                                                Eliminar
                                            </span>
                                </a>

                                {{--<div class="btn-group dropdown" style="width: 30%">
                                    <button type="button" class="btn btn-default btn-sm btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Más <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a href="{{ url('articulos/' . $articulo['id'] . '/edit') }}">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                Editar
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ url('articulos/' . $articulo['id']) }}"
                                               data-method="delete"
                                               data-token="{{ csrf_token() }}"
                                               data-confirm="Esta seguro que desea eliminar a articulo con nombre {{ $articulo->nombre }}?">
                                                <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                                <span class="text-danger">
                                                Eliminar
                                            </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay articulos
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/articulos/listar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/delete-link.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

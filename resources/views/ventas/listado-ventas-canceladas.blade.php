@extends('layouts.app')

@section('site-name', 'Listando ventas')

@section('page-header', session('LOCAL_NOMBRE'))
@section('page-description', 'Ventas Canceladas')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Ventas Canceladas</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            <div class="box-header">
                <h3 class="box-title">Ventas Canceladas</h3>
            </div>
        </div>

        <div class="panel-body">
            @if (count($ventas_canceladas) > 0)
                <table class="table table-bordered table-hover" id="ventas" style="width:100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th></th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($ventas_canceladas as $venta)
                        <tr>
                            <td>{{ $venta->created_at }}</td>
                            <td>
                                <a href="{{ url('usuarios/' . $venta->Usuario->id) }}">
                                    {{ $venta->Usuario->nombre }} {{ $venta->Usuario->apellido }}
                                </a>
                            </td>
                            <td>
                                @if ($venta->Cliente)
                                    {{ $venta->Cliente->nombre }} {{ $venta->Usuario->apellido }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ date("d/m/Y H:i", strtotime($venta->created_at)) }}</td>
                            <td>{{ $venta->motivo }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay ventas canceladas
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/ventas/listar.js') }}"></script>
@stop

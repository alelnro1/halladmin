@extends('layouts.app')

@section('site-name', 'Listando cambios')

{{-- Esta sección deberá mostrar una alerta si no se abrió la caja --}}
@section('alerta-caja-sin-abrir')
    @include('commons.alerta-caja-sin-abrir')
@endsection

@section('page-description', 'Cambios')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Cambios</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-header">
                <h3 class="box-title">Listado</h3>
                @if (Auth::user()->tieneModuloHabilitado('nuevo-cambio') || Auth::user()->esSuperAdmin())
                    <div class="box-tools">
                        <a href="{{ url('cambios/nuevo-cambio') }}" class="btn btn-block btn-success btn-sm">
                            <i class="fa fa-fw fa-plus" aria-hidden="true"></i>
                            Nuevo Cambio
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="box-body">
            @if (count($cambios) > 0)
                <table class="table table-bordered table-hover" id="ventas" style="width:100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th></th>
                        <th>Orden</th>
                        <th>Artículo Devuelto</th>
                        <th>Monto Total</th>
                        <th>Artículos Comprados</th>
                        <th>Vendedor</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($cambios as $cambio)
                        <tr>
                            <td>{{ $cambio->created_at }}</td>
                            <td class="text-right">
                                <a href="{{ url('ventas/ver/' . $cambio->Venta->nro_orden) }}">
                                    #{{ $cambio->Venta->nro_orden }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('articulos/' . $cambio->Articulo->id) }}">
                                    {{ $cambio->Articulo->DatosArticulo->descripcion }}
                                </a>
                            </td>
                            <td class="text-right">
                                ${{ number_format($cambio->Venta->monto_total, 2) }}
                            </td>
                            <td>{{ count($cambio->Venta->Articulos) }}</td>
                            <td>
                                <a href="{{ url('usuarios/' . $cambio->Venta->Usuario->id) }}">
                                    {{ $cambio->Venta->Usuario->nombre }} {{ $cambio->Venta->Usuario->apellido }}
                                </a>
                            </td>
                            <td>{{ date("d/m/Y H:i:s", strtotime($cambio->created_at)) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay cambios
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.13/sorting/date-euro.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            jQuery.extend( jQuery.fn.dataTableExt.oSort, {
                "sikor-date-pre": function(a) {
                    var dateParts = a.split('.'),
                            time = a.split(' ')[1].split(':'),
                            year = parseInt(dateParts[2])-1900,
                            month = parseInt(dateParts[1])-1,
                            day = parseInt(dateParts[0]),
                            hours = parseInt(time[0]),
                            mins = parseInt(time[1]),
                            secs = parseInt(time[2]);
                    return Date.UTC(year, month, day, hours, mins, secs);
                },
                "sikor-date-asc": function(a, b) {
                    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
                },
                "sikor-date-desc": function(a,b) {
                    return ((a < b) ? 1 : ((a > b) ? -1 : 0));
                }
            });

            $('#ventas').DataTable({
                responsive: true,
                columnDefs: [
                    {
                        "targets": [ 0 ],
                        "visible": false
                    },
                    { type: 'date-euro', targets: 6 }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ ",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered": "(filtrado de un total de _MAX_)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "select": {
                        "rows": "(%d artículo/s elegidos)"
                    }
                }
            });
        })
    </script>
@stop

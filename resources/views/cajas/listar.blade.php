@extends('layouts.app')

@section('site-name', 'Listando registros de caja')

@section('page-header', $LOCAL_NOMBRE)
@section('page-description', 'Registros de Caja')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Caja</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <div class="box-header">
                <h3 class="box-title">Listado</h3>
            </div>
        </div>
        <div class="box-body">
            @if (count($cajas) > 0)
                <table class="table table-bordered table-hover" id="ventas" style="width:100%">
                    <!-- Table Headings -->
                    <thead>
                    <tr>
                        <th></th>
                        <th>Usuario</th>
                        <th>Monto Apertura</th>
                        <th>Monto Cierre</th>
                        <th>Fecha Apertura</th>
                        <th>Fecha Cierre</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($cajas as $caja)
                        <tr>
                            <td>{{ $caja->created_at }}</td>
                            <td>
                                <a href="{{ url('usuarios/' . $caja->User->id) }}">
                                    {{ $caja->User->nombre }} {{ $caja->User->apellido }}
                                </a>
                            </td>
                            <td class="text-right">
                                ${{ number_format($caja->apertura, 2) }}
                            </td>
                            <td class="text-right">
                                @if ($caja->cierre)
                                    ${{ number_format($caja->cierre, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">
                                {{ date("d/m/Y H:i:s", strtotime($caja->date_apertura)) }}
                            </td>
                            <td class="text-right">
                                @if ($caja->date_cierre)
                                    {{ date("d/m/Y H:i:s", strtotime($caja->date_cierre)) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                No hay registros de caja
            @endif
        </div>
    </div>
@endsection

@section('javascript')
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            jQuery.extend( jQuery.fn.dataTableExt.oSort, {
                "sikor-date-pre": function(a) {
                    var dateParts = a.split('/'),
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
                "aoColumnDefs": [
                    {"sType": "sikor-date", "aTargets": [4, 5]},
                    { visible: false, targets: [0]}
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
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
@stop

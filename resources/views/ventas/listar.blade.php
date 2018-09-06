@extends('layouts.app')

@section('site-name', 'Listando ventas')

@section('page-description', 'Ventas')

@section('styles')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css">
@stop

@section('niveles')
    <li><a href="#"><i class="fa fa-dashboard"></i> Ventas</a></li>
    <li class="active">Listado</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="panel-heading">
            <div class="box-header">
                <h3 class="box-title">Listado</h3>

                @if (Auth::user()->tieneModuloHabilitado('nueva-venta'))
                    <div class="box-tools">
                        <a href="{{ url('ventas/nueva-venta') }}" class="btn btn-block btn-success btn-sm">
                            <i class="fa fa-fw fa-plus" aria-hidden="true"></i>
                            Nueva Venta
                        </a>
                    </div>
                @elseif (Auth::user()->esSuperAdmin())
                    <div class="box-tools">
                        <a href="{{ url('ventas/nueva-venta') }}" class="btn btn-block btn-success btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Nueva Venta
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="panel-body">
            @if (count($ventas) > 0)
                @include('ventas.listado-ventas')
            @else
                No hay ventas
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
            $('#ventas').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 },
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
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
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

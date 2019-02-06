<table class="table table-bordered table-hover table-striped" id="ventas" style="width:100%">
    <!-- Table Headings -->
    <thead>
    <tr>
        <th></th>
        <th>Orden</th>
        <th class="col-xs-2">Cantidad de Artículos</th>
        <th class="col-xs-2">Monto Total</th>
        <th>Medio de Pago</th>
        <th>Vendedor</th>
        <th>Fecha</th>
        <th></th>
    </tr>
    </thead>

    <tbody>
    @if (count($ventas) < 0)
        <tr>
            <td>No hay ventas</td>
        </tr>
    @else
        @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->created_at }}</td>
                <td class="text-right">
                    <a href="{{ route('ventas.ver', ['venta' => $venta->getNroOrden()]) }}">
                        #{{ $venta->getNroOrden() }}
                    </a>
                </td>
                <td class="text-right">{{ $venta->cantidad_articulos }}</td>
                <td class="text-right">${{ number_format($venta->monto_total, 2) }}</td>
                <td class="text-right">
                    @if ($venta->getMedioPago() != "")
                        {{ $venta->getMedioPago() }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('usuarios.view', ['usuario' => $venta->Usuario->id]) }}">
                        {{ $venta->Usuario->nombre }} {{ $venta->Usuario->apellido }}
                    </a>
                </td>
                <td>{{ date("d/m/Y H:i:s", strtotime($venta->created_at)) }}</td>
                <td>
                    <a href="{{ route('ventas.ver', ['venta' => $venta->nro_orden]) }}" class="btn btn-xs btn-default">
                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;
                        Ver
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<div class="box box-widget">
    <div class="box-header with-border">
        <h3 class="box-title">Compras</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th># Orden</th>
                <th>Cant Artículos</th>
                <th>Total</th>
                <th>Medio de Pago</th>
                <th>Local</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($compras as $compra)
                <tr>
                    <td>
                        <a href="{{ route('ventas.ver', ['compra' => $compra->nro_orden]) }}">
                            {{ $compra->getNroOrden() }}
                        </a>
                    </td>
                    <td>{{ $compra->getCantidadArticulos() }}</td>
                    <td>{{ $compra->getMontoTotal() }}</td>
                    <td>{{ $compra->getMedioPago() }}</td>
                    <td>{{ $compra->getNombreLocal() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
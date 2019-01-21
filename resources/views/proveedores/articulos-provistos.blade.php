<fieldset>
    <legend>Artículos Provistos</legend>

    @if (count($articulos) > 0)
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Local</th>
                <th>Artículo</th>
                <th>Cantidad Provista</th>
                <th>Costo de Compra</th>
                <th>Precio de Venta</th>
                <th>Fecha de Compra</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($articulos as $articulo)
                <tr>
                    <td>
                        <a href="{{ url('locales/' . $articulo->Local->id) }}">{{ $articulo->Local->nombre }}</a>
                    </td>
                    <td>
                        <a href="{{ url('articulos/' . $articulo->id) }}">
                            {{ $articulo->DatosArticulo->descripcion }}
                        </a>
                    </td>
                    <td>
                        {{ $articulo->pivot->cantidad }}
                    </td>
                    <td>
                        ${{ number_format($articulo->pivot->costo, 2) }}
                    </td>
                    <td>
                        ${{ number_format($articulo->getPrecio(), 2) }}
                    </td>
                    <td>
                        {{ date("d/m/Y", strtotime($articulo->pivot->created_at)) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        El proveedor no ha ingresado artículos
    @endif
</fieldset>
<fieldset>
    <legend>Datos</legend>

    <table class="table table-striped task-table" style="margin-bottom: 20px;">
        <tr>
            <td><strong>Código</strong></td>
            <td>{{ $articulo->getCodigo() }}</td>
        </tr>
        <tr>
            <td><strong>Descripción</strong></td>
            <td>{{ $articulo->getDescripcion() }}</td>
        </tr>

        <tr>
            <td><strong>Género</strong></td>
            <td>{{ $articulo->getNombreGenero() }}</td>
        </tr>

        <tr>
            <td><strong>Categoría</strong></td>
            <td>{{ $articulo->getNombreCategoria() }}</td>
        </tr>

        {{--<tr>
            <td><strong>Talle</strong></td>
            <td>{{ $articulo->talle->nombre }}</td>
        </tr>

        <tr>
            <td><strong>Color</strong></td>
            <td>{{ $articulo->color }}</td>
        </tr>

        <tr>
            <td><strong>Stock Disponible</strong></td>
            <td>{{ $articulo->cantidad }}</td>
        </tr>--}}

        <tr>
            <td><strong>Precio de Venta</strong></td>
            <td>${{ number_format($articulo->getPrecio(), 2) }}</td>
        </tr>
    </table>
</fieldset>
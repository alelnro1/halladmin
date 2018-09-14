<fieldset>
    <legend>Proveedores</legend>

    @if (count($proveedores) > 0)

        <table class="table table-bordered table-hover table-striped" id="proveedores" style="width:100%">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad Comprada</th>
                <th>Costo de Compra</th>
                <th>Fecha de Compra</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($proveedores as $proveedor)
                <tr>
                    <td>
                        <strong>{{ $proveedor->nombre }}</strong>
                    </td>
                    <td>{{ $proveedor->pivot->cantidad }}</td>
                    <td>${{ number_format($proveedor->pivot->costo, 2) }}</td>
                    <td>{{ date("d/m/Y", strtotime($proveedor->pivot->created_at)) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        No se han cargado proveedores para el artículo.
    @endif
</fieldset>
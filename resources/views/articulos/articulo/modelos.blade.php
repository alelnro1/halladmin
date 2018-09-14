<fieldset>
    <legend>Modelos</legend>

    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>Color</th>
            <th>Talle</th>
            <th>Cantidad Disponible</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($articulos_iguales as $articulo_ig)
            <tr>
                <td>{{ $articulo_ig->color }}</td>
                <td>{{ $articulo_ig->Talle->nombre }}</td>
                <td>{{ $articulo_ig->cantidad }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</fieldset>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Cod Artículo</th>
        <th>Descripción</th>
    </tr>
    </thead>

    <tbody>
        @foreach($articulos as $articulo)
            <tr>
                <td>{{ $articulo->getCodigo() }}</td>
                <td>{{ $articulo->getNombre() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
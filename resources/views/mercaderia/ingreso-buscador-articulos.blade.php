<div id="dialog-articulos" style="display:none;">
    <table class="table table-bordered table-hover" id="mercaderia-existente">
        <thead>
        <tr>
            <th>Código</th>
            <th>Descripción</th>
            <th>Color</th>
            <th>Talle</th>
            <th>Categoria</th>
            <th></th>
        </tr>
        </thead>

        @if(count($mercaderia_existente) > 0)
            <tbody>
            @foreach ($mercaderia_existente as $articulo)
                <tr>
                    <td>{{ $articulo->DatosArticulo->codigo }}</td>
                    <td>{{ $articulo->DatosArticulo->descripcion }}</td>
                    <td>{{ $articulo->color }}</td>
                    <td>{{ $articulo->Talle->nombre }}</td>
                    <td>{{ $articulo->DatosArticulo->Categoria->nombre }}</td>
                    <td>
                        <a href="#" class="elegir-articulo btn btn default btn-sm" data-articulo="{{ $articulo }}">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;
                            Elegir
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        @endif
    </table>
</div>
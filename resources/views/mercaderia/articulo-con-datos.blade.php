<tr class="datos-articulo @if($articulo['existe'] == 'si') existe @endif">
    <!-- CODIGO -->
    <td class="fila-codigo">
        <input type="text"
               id="{{ 'codigo_' . str_random(8) }}"
               data-id="codigo"
               name="codigo[]"
               class="form-control codigo"
               autocomplete="off"
               value="{{ $articulo['codigo'] }}"
               @if($articulo['existe'] == 'si') disabled @endif>
    </td>

    <!-- DESCRIPCION -->
    <td class="descripcion">
        <input type="text"
               id="{{ 'descripcion_' . str_random(8) }}"
               data-id="descripcion"
               name="descripcion[]"
               class="form-control descripcion"
               autocomplete="off"
               value="{{ $articulo['descripcion'] }}"
        @if($articulo['existe'] == 'si') disabled @endif>
    </td>


    <!-- GENERO -->
    <td class="fila-genero">
        <select name="genero[]"
                class="form-control genero selectpicker required"
                id="{{ 'genero_' . str_random(8) }}"
                data-msg="Este campo es obligatorio"
                data-id="genero">
            <option value="">Elija...</option>
            @foreach ($generos as $genero)
                <option value="{{ $genero->id }}" @if($genero->id == $articulo['genero']) selected @endif>
                    {{ $genero->nombre }}
                </option>
            @endforeach
        </select>
    </td>


    <!-- CATEGORIA -->
    <td class="fila-categoria">
        @include('commons.select-categoria', [
            'page' => 'hay datos',
            'categoria_seleccionada' => $articulo['categoria_id']
        ])
    </td>

    <!-- TALLES -->
    <td class="talles">
        <select name="talle_id[]"
                id="{{ 'talle_id_' . str_random(8) }}"
                data-id="talle_id"
                class="form-control talle_id selectpicker required"
                autocomplete="off"
                data-msg="Este campo es obligatorio"
                data-height="50px"
                data-live-search="true"
                data-live-search-placeholder="Buscar"
                data-talle-seleccionado="{{ $articulo['talle_id'] }}">
            <option value="" selected>Elija...</option>

            @foreach ($talles as $talle)
                <option value="{{ $talle->id }}" @if($talle->id == $articulo['talle_id']) selected @endif>{{ $talle->nombre }}</option>
            @endforeach
        </select>
    </td>

    <!-- COLOR -->
    <td>
        <input type="text"
               id="{{ 'color_' . str_random(8) }}"
               data-id="color"
               name="color[]"
               class="form-control color"
               autocomplete="off"
               value="{{ $articulo['color'] }}">
    </td>

    <!-- PROVEEDOR -->
    <td>
        <select name="proveedor_id[]"
                id="{{ 'proveedor_id_' . str_random(8) }}"
                data-id="proveedor_id"
                class="form-control proveedor_id selectpicker"
                autocomplete="off">
            <option value="">Elija...</option>

            @foreach ($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}"
                        @if (isset($articulo['proveedor_id']) && $articulo['proveedor_id'] == $proveedor->id)
                            selected
                        @endif>
                    {{ $proveedor->nombre }}
                </option>
            @endforeach
        </select>
    </td>

    <!-- COSTO -->
    <td>
        <div class="input-group">
            <span class="input-group-addon" style="padding: 5px;">$</span>
            <input type="text"
                   id="{{ 'costo_' . str_random(8) }}"
                   value="{{ $articulo['costo'] }}"
                   data-id="costo"
                   name="costo[]"
                   class="form-control costo"
                   autocomplete="off">
        </div>
        <label for="costo"></label>
    </td>

    <!-- PRECIO -->
    <td class="fila-precio">
        <div class="input-group">
            <span class="input-group-addon" style="padding: 5px;">$</span>
            <input type="text"
                   id="{{ 'precio_' . str_random(8) }}"
                   data-id="precio"
                   name="precio[]"
                   class="form-control precio"
                   value="{{ $articulo['precio'] }}"
                   autocomplete="off"
                   @if($articulo['existe'] == 'si') disabled @endif>
        </div>
        <label for="precio"></label>
    </td>

    <!-- CANTIDAD -->
    <td class="fila-cantidad">
        <input type="number"
               step="1"
               min="1"
               id="{{ 'cantidad_' . str_random(8) }}"
               data-id="cantidad"
               name="cantidad[]"
               class="form-control cantidad"
               value="{{ $articulo['cantidad'] }}"
               autocomplete="off">
    </td>

    <td>
        <button class="btn btn-danger eliminar-articulo">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
        </button>
    </td>
</tr>



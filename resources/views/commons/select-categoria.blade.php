<select name="categoria_id[]"
        id="categoria_id_{{ str_random(8) }}"
        data-id="categoria_id"
        class="form-control selectpicker categoria_id required"
        data-msg="Este campo obligatorio"
        data-live-search="true"
        data-live-search-placeholder="Buscar">

    @foreach ($categorias as $categoria)
        @if ($page == "hay datos")
            <option value="{{ $categoria->id }}"
                    @if($categoria_seleccionada == $categoria->id) selected @endif>
                {{ $categoria->nombre }}
            </option>
        @else
            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
        @endif
    @endforeach

    {{--@foreach($categorias as $categoria_padre)
        @if($categoria_padre->padre_id == null)
            <optgroup label="{{ $categoria_padre->nombre }}">
                @foreach($categorias as $categoria_hijo)
                    @if($categoria_hijo->padre_id && $categoria_hijo->padre_id == $categoria_padre->id)
                        @if ($page == "hay datos")
                            <option value="{{ $categoria_hijo->id }}"
                                    @if($categoria_seleccionada == $categoria_hijo->id) selected @endif>
                                {{ $categoria_hijo->nombre }}
                            </option>
                        @else
                            <option value="{{ $categoria_hijo->id }}">{{ $categoria_hijo->nombre }}</option>
                        @endif
                    @endif
                @endforeach
            </optgroup>
        @endif
    @endforeach--}}
</select>

{{--@if ($errors->has('categoria_id'))
    <span class="help-block">
        <strong>{{ $errors->first('categoria_id') }}</strong>
    </span>
@endif--}}

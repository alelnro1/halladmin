<div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
    <label for="nombre">Nombre</label>

    <input type="text"
           class="form-control"
           name="nombre"
           value="{{ old('nombre') }}"
           placeholder="Escriba el nombre">

    @if ($errors->has('nombre'))
        <span class="help-block">
                                            <strong>{{ $errors->first('nombre') }}</strong>
                                        </span>
    @endif
</div>
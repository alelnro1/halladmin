<div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }}">
    <label for="descripcion">Descripcion</label>

    <textarea
        maxlength="150"
        class="form-control"
        name="descripcion"
        placeholder="Escriba la descripción"
    >{{ old('descripcion') }}</textarea>

    @if ($errors->has('descripcion'))
    <span class="help-block">
            <strong>{{ $errors->first('descripcion') }}</strong>
        </span>
    @endif
</div>
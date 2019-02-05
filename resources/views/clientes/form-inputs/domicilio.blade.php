<div class="form-group{{ $errors->has('domicilio') ? ' has-error' : '' }}">
    <label for="domicilio">Domicilio</label>

    <input type="text"
           class="form-control"
           name="domicilio"
           autocomplete="off"
           value="{{ old('domicilio') }}"
           placeholder="Escriba el domicilio del cliente">

    @if ($errors->has('domicilio'))
        <span class="help-block">
                    <strong>{{ $errors->first('domicilio') }}</strong>
                </span>
    @endif
</div>
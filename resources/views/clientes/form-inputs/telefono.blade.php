<div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
    <label for="telefono">Teléfono</label>

    <input type="text"
           class="form-control"
           name="telefono"
           autocomplete="off"
           value="{{ old('telefono') }}"
           placeholder="Escriba el teléfono del cliente">

    @if ($errors->has('telefono'))
        <span class="help-block">
                                    <strong>{{ $errors->first('telefono') }}</strong>
                                </span>
    @endif
</div>
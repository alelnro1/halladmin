<div class="form-group {{ $errors->has('apellido') ? ' has-error' : '' }}">
    <label for="apellido">Apellido</label>

    <input type="text"
           class="form-control"
           name="apellido"
           value="{{ old('apellido') }}"
           placeholder="Escriba el apellido del cliente">

    @if ($errors->has('apellido'))
        <span class="help-block">
                                    <strong>{{ $errors->first('apellido') }}</strong>
                                </span>
    @endif
</div>
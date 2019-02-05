<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label for="email">Email</label>

    <input type="email"
           class="form-control"
           name="email"
           autocomplete="off"
           value="{{ old('email') }}"
           placeholder="Escriba el email del cliente">

    @if ($errors->has('email'))
        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
    @endif
</div>
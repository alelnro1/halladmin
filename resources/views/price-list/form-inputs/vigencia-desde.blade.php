<div class="form-group {{ $errors->has('vigencia_desde') ? ' has-error' : '' }}">
    <div class="input-group date">
        <span class="input-group-addon" id="basic-addon1">Desde</span>
        <input type="text"
               class="form-control datepicker"
               name="vigencia_desde"
               autocomplete="off"
               value="{{ old('vigencia_desde') }}">
    </div>

    @if ($errors->has('vigencia_desde'))
        <span class="help-block">
            <strong>{{ $errors->first('vigencia_desde') }}</strong>
        </span>
    @endif
</div>
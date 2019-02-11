<div class="form-group {{ $errors->has('vigencia_hasta') ? ' has-error' : '' }}">
    <div class="input-group date">
        <span class="input-group-addon" id="basic-addon1">Hasta</span>
        <input type="text"
               class="form-control datepicker"
               autocomplete="off"
               value="{{ old('vigencia_hasta') }}"
               name="vigencia_hasta">
    </div>

    @if ($errors->has('vigencia_hasta'))
        <span class="help-block">
            <strong>{{ $errors->first('vigencia_hasta') }}</strong>
        </span>
    @endif
</div>
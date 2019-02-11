<div class="form-group {{ $errors->has('activo') ? ' has-error' : '' }}">
    <input type="checkbox"
           name="activo"
           @if(old('activo')) checked @endif/> Marque si desea que la lista este <strong>activa</strong>

    @if ($errors->has('activo'))
        <span class="help-block">
            <strong>{{ $errors->first('activo') }}</strong>
        </span>
    @endif
</div>
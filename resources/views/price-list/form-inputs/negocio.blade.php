<div class="form-group {{ $errors->has('negocio') ? ' has-error' : '' }}">
    <input type="checkbox"
           name="negocio"
           @if(old('negocio')) checked @endif/> Marque si desea que la lista se aplique a todo el negocio

    @if ($errors->has('negocio'))
        <span class="help-block">
            <strong>{{ $errors->first('negocio') }}</strong>
        </span>
    @endif
</div>
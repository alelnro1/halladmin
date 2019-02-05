<div class="form-group{{ $errors->has('cuit') ? ' has-error' : '' }}">
    <label for="cuit">CUIT</label>

    <div class="row">
        <div class="col-xs-7">
            <input type="text" class="form-control" name="cuit" value="{{ old('cuit') }}"
                   autocomplete="off"
                   id="cuit"
                   data-mask="99-99999999-9"
                   placeholder="Escriba el CUIT">

            @if ($errors->has('cuit'))
                <span class="help-block">
                                <strong>{{ $errors->first('cuit') }}</strong>
                            </span>
            @endif
        </div>
        <div class="col-xs-5">
            <button class="btn btn-default" id="buscar-contribuyente"
                    data-buscar-contribuyente-url="{{ route('afip.get-info-contribuyente') }}">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</div>
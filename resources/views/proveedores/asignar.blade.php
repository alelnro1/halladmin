<fieldset>
    <legend>Asignar proveedor existente al local</legend>

    <form action="{{ route('proveedores.asignar-proveedor') }}" method="POST">
        @csrf
        <div class="help-block">
            Seleccione un proveedor ya existente para asignar al local actual.
        </div>

        <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label"></label>

            <div class="col-md-6">
                <select name="proveedor" id="" class="form-control" required>
                    <option value="">Seleccione un proveedor...</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                    @endforeach
                </select>

                @if ($errors->has('nombre'))
                    <span class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </span>
                @endif
            </div>

            <br>
            <br>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-btn fa-link"></i>&nbsp;Asignar
                    </button>
                </div>
            </div>
        </div>
    </form>
</fieldset>
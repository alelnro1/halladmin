<form role="form" action="{{ url('clientes') }}" method="POST">
    {{ csrf_field() }}
    <div class="box-body">
        <!-- Nombre -->
        <div class="form-group {{ $errors->has('nombre') ? ' has-error' : '' }}">
            <label for="nombre">Nombre</label>

            <input type="text"
                   class="form-control"
                   name="nombre"
                   value="{{ old('nombre') }}"
                   placeholder="Escriba el nombre del cliente">

            @if ($errors->has('nombre'))
                <span class="help-block">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </span>
            @endif
        </div>

        <!-- Apellido -->
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

        <!-- Email -->
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

        <!-- Telefono -->
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

        <!-- Domicilio -->
        <div class="form-group{{ $errors->has('domicilio') ? ' has-error' : '' }}">
            <label for="telefono">Domicilio</label>

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


        <!-- Domicilio -->
        <div class="form-group{{ $errors->has('tipo_contribuyente') ? ' has-error' : '' }}">
            <label for="tipo_contribuyente">Tipo Contribuyente</label>

            <select name="tipo_contribuyente" id="tipo_contribuyente" class="form-control">
                <option value="">Seleccione...</option>
                <option value="responsable_inscripto">Responsable Inscripto en IVA</option>
                <option value="monotributista">Monotributista</option>
                <option value="consumidor_final">Consumidor Final</option>
            </select>

            @if ($errors->has('tipo_contribuyente'))
                <span class="help-block">
                    <strong>{{ $errors->first('tipo_contribuyente') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('cuit') ? ' has-error' : '' }}">
            <label for="cuit">CUIT</label>

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
            <button class="btn btn-default" id="buscar-contribuyente"
                    data-buscar-contribuyente-url="{{ route('afip.get-info-contribuyente') }}">
                Buscar
            </button>
        </div>

        <br>

        <div id="cargando-datos-contribuyente" style="display: none;">
            <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>
        </div>

        <br>

    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </div>
</form>
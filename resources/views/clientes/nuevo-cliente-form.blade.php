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

    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </div>
</form>
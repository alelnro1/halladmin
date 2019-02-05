<form role="form" action="{{ url('clientes') }}" method="POST">
    {{ csrf_field() }}
    <div class="box-body">

        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-4">
                    @include('clientes.form-inputs.nombre')
                </div>
                <div class="col-xs-4"></div>
                <div class="col-xs-4"></div>
            </div>
        </div>
        <!-- Nombre -->


        <!-- Apellido -->
        @include('clientes.form-inputs.apellido')

    <!-- Email -->
        @include('clientes.form-inputs.email')

        <!-- Telefono -->
        @include('clientes.form-inputs.telefono')

        <!-- Domicilio -->
        @include('clientes.form-inputs.domicilio')


        <!-- Tipo de Contribuyente -->
        @include('clientes.form-inputs.tipo-contribuyente')

        @include('clientes.form-inputs.cuit')

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
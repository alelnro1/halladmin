<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $cantidad_ventas }}</h3>

                <p>
                    @if ($cantidad_ventas == 1)
                        Venta
                    @else
                        Ventas
                    @endif
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
            </div>
            <a href="{{ url('ventas') }}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $cantidad_cambios }}</h3>

                <p>
                    @if ($cantidad_cambios == 1)
                        Cambio
                    @else
                        Cambios
                    @endif
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-exchange" aria-hidden="true"></i>
            </div>
            <a href="{{ url('cambios') }}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $cantidad_usuarios }}</h3>

                <p>
                    @if ($cantidad_usuarios == 1)
                        Usuario
                    @else
                        Usuarios
                    @endif
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-users" aria-hidden="true"></i>
            </div>
            <a href="{{ url('usuarios') }}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $cantidad_ventas_canceladas }}</h3>

                <p>
                    @if ($cantidad_ventas_canceladas == 1)
                        Venta Cancelada
                    @else
                        Ventas Canceladas
                    @endif
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-ban" aria-hidden="true"></i>
            </div>
            <a href="{{ url('ventas-canceladas') }}" class="small-box-footer">Ver más <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
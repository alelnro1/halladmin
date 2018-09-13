<ul class="sidebar-menu">
    <li @if(Request::is('home/*')) class="active" @endif>
        <a href="{{ url('home') }}">
            <i class="fa fa-tachometer" aria-hidden="true"></i>
            <span>Inicio</span>
        </a>
    </li>

    <li @if(Request::is('locales*') ) class="active" @endif>
        <a href="{{ url('/locales') }}">
            <i class="fa fa-building" aria-hidden="true"></i>
            <span>Locales</span>
        </a>
    </li>

    <li @if(Request::is('proveedores*') ) class="active" @endif>
        <a href="{{ route('proveedores') }}">
            <i class="fa fa-truck" aria-hidden="true"></i>
            <span>Proveedores</span>
        </a>
    </li>

    <li @if(Request::is('usuarios') ) class="active" @endif>
        <a href="{{ url('/usuarios') }}">
            <i class="fa fa-users" aria-hidden="true"></i>
            <span>Usuarios</span>
        </a>
    </li>

    <li @if(Request::is('ventas') ) class="active" @endif>
        <a href="{{ url('/ventas') }}">
            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
            <span>Ventas</span>
        </a>
    </li>

    <li @if(Request::is('cambios') ) class="active" @endif>
        <a href="{{ url('/cambios') }}">
            <i class="fa fa-exchange" aria-hidden="true"></i>
            <span>Cambios</span>
        </a>
    </li>

    <li @if(Request::is('caja') ) class="active" @endif>
        <a href="{{ url('/caja') }}">
            <i class="fa fa-archive" aria-hidden="true"></i>
            <span>Caja</span>
        </a>
    </li>

    {{--}}<li @if(Request::is('movimientos') ) class="active" @endif>
        <a href="{{ url('/cambios') }}">
            <i class="fa fa-exchange" aria-hidden="true"></i>
            <span>Cambios</span>
        </a>
    </li>--}}

    {{--}}<li @if(Request::is('alarmas') ) class="active" @endif>
        <a href="{{ url('/alarmas') }}">
            <i class="fa fa-exchange" aria-hidden="true"></i>
            <span>Alarmas</span>
        </a>
    </li>--}}

    <li class="treeview" @if(Request::is('mercaderia*') ) class="active" @endif>
        <a href="#"><i class="fa fa-briefcase"></i> <span>Mercadería</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{ route('mercaderia') }}">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    Actual
                </a>
            </li>

            <li class="">
                <a href="{{ route('mercaderia.ingresar') }}">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    <span>Ingreso</span>
                </a>
            </li>
        </ul>
    </li>
</ul>
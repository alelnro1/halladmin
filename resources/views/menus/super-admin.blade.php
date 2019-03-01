<ul class="sidebar-menu">
    <li @if(Request::is('home/*')) class="active" @endif>
        <a href="{{ url('home') }}">
            <i class="fa fa-tachometer-alt" aria-hidden="true"></i>
            <span>Inicio</span>
        </a>
    </li>


    <li @if(Request::is('mi-negocio*') ) class="active" @endif>
        <a href="{{ url('/mi-negocio') }}">
            <i class="fa fa-building" aria-hidden="true"></i>
            <span>Mi Negocio</span>
        </a>
    </li>

    <li @if(Request::is('locales*') ) class="active" @endif>
        <a href="{{ route('locales') }}">
            <i class="fa fa-building" aria-hidden="true"></i>
            <span>Locales</span>
        </a>
    </li>

    <li @if(Request::is('clientes') ) class="active" @endif>
        <a href="{{ url('/clientes') }}">
            <i class="fa fa-users" aria-hidden="true"></i>
            <span>Clientes</span>
        </a>
    </li>

    {{--<li @if(Request::is('lista-precios*') ) class="active" @endif>
        <a href="{{ url('lista-precios') }}">
            <i class="fa fa-dollar-sign" aria-hidden="true"></i>
            <span>Listas de Precios</span>
        </a>
    </li>--}}

    <li @if(Request::is('proveedores*') ) class="active" @endif>
        <a href="{{ route('proveedores') }}">
            <i class="fa fa-truck" aria-hidden="true"></i>
            <span>Proveedores</span>
        </a>
    </li>

    <li @if(Request::is('caja') ) class="active" @endif>
        <a href="{{ url('/caja') }}">
            <i class="fa fa-archive" aria-hidden="true"></i>
            <span>Caja</span>
        </a>
    </li>

    <li @if(Request::is('usuarios') ) class="active" @endif>
        <a href="{{ url('/usuarios') }}">
            <i class="fa fa-user-tie" aria-hidden="true"></i>
            <span>Usuarios</span>
        </a>
    </li>

    <li @if(Request::is('administradores') ) class="active" @endif>
        <a href="{{ url('/administradores') }}">
            <i class="fa fa-user-secret" aria-hidden="true"></i>
            <span>Administradores</span>
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
            <i class="fa fa-exchange-alt" aria-hidden="true"></i>
            <span>Cambios</span>
        </a>
    </li>

    <li class="treeview @if(Request::is('mercaderia*') ) active @endif">
        <a href="#"><i class="fa fa-briefcase"></i> <span>Mercadería</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu @if(Request::is('mercaderia*') ) menu-open @endif">
            <li>
                <a href="{{ url('/mercaderia') }}">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    Actual
                </a>
            </li>

            <li class="">
                <a href="{{ url('/mercaderia/ingreso') }}">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    <span>Ingreso</span>
                </a>
            </li>
        </ul>
    </li>
</ul>
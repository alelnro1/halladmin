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
        <a href="{{ url('/proveedores') }}">
            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            <span>Proveedores</span>
        </a>
    </li>

    <li @if(Request::is('ofertas*') ) class="active" @endif>
        <a href="{{ url('/ofertas') }}">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
            <span>Ofertas</span>
        </a>
    </li>

    <li @if(Request::is('categorias') ) class="active" @endif>
        <a href="{{ url('/categorias') }}">
            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            <span>Categorías</span>
        </a>
    </li>

    <li @if(Request::is('talles*') ) class="active" @endif>
        <a href="{{ url('/talles') }}">
            <i class="fa fa-hand-o-right" aria-hidden="true"></i>
            <span>Talles</span>
        </a>
    </li>

    <li class="treeview">
        <a href="#"><i class="fa fa-link"></i> <span>Mercadería</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{ url('/mercaderia') }}">
                    Actual
                </a>
            </li>

            <li class="">
                <a href="{{ url('/mercaderia/ingreso') }}">
                    <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                    <span>Ingreso</span>
                </a>
            </li>
        </ul>
    </li>

    <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
    <li class="treeview">
        <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="#">Link in level 2</a></li>
            <li><a href="#">Link in level 2</a></li>
        </ul>
    </li>
</ul>
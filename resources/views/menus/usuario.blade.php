<ul class="sidebar-menu">
    @foreach ($MODULOS_HABILITADOS as $menu)
        @if (count($menu->MenusHijos) > 0 && $menu->nombre == "mercaderia")
            <li class="treeview @if(Request::is('mercaderia*') ) active @endif">
                <a href="#">
                    <i class="{{ $menu->icon }}"></i>
                    <span>{{ ucfirst($menu->nombre) }}</span>

                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @foreach ($menu->MenusHijos as $menu_hijo)
                        <li>
                            <a href="{{ url('/' . $menu->nombre . '/' . $menu_hijo->nombre) }}">
                                <i class="{{ $menu_hijo->icon }}" aria-hidden="true"></i>
                                {{ ucfirst($menu_hijo->nombre) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li @if(Request::is($menu->nombre . '*') ) class="active" @endif>
                <a href="{{ url('/' . strtolower($menu->nombre)) }}">
                    <i class="{{ $menu->icon }}" aria-hidden="true"></i>
                    <span>{{ ucfirst($menu->nombre) }}</span>
                </a>
            </li>
        @endif
    @endforeach
</ul>

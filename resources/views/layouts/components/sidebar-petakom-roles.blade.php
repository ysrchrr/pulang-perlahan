<ul class="navbar-nav" id="navbar-nav">
    @if (isset($menuParents) && isset($menus))
        @foreach ($menuParents as $parent)
            @if (isset($menus[$parent->id]) && $menus[$parent->id]->count() > 0)
                <!-- Menu Title / Parent -->
                <li class="menu-title">
                    <span data-key="t-menu">{{ $parent->parent_name }}</span>
                </li>

                <!-- Menu Items under this parent -->
                @foreach ($menus[$parent->id] as $menu)
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is($menu->slug_name . '*') ? 'active' : '' }}"
                            href="{{ url('/' . $menu->slug_name) }}">
                            @if ($menu->icon)
                                {!! $menu->icon !!}
                            @else
                                <i class="ri-file-line"></i>
                            @endif
                            <span data-key="t-widgets">{{ $menu->menu_name }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        @endforeach

        <!-- Menu tanpa parent (parent_id = NULL) -->
        @if (isset($menus[null]) && $menus[null]->count() > 0)
            <li class="menu-title"><span data-key="t-menu">Lainnya</span></li>
            @foreach ($menus[null] as $menu)
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is($menu->slug_name . '*') ? 'active' : '' }}"
                        href="{{ url('/' . $menu->slug_name) }}">
                        @if ($menu->icon)
                            {!! $menu->icon !!}
                        @else
                            <i class="ri-file-line"></i>
                        @endif
                        <span data-key="t-widgets">{{ $menu->menu_name }}</span>
                    </a>
                </li>
            @endforeach
        @endif
    @else
        <!-- Fallback kalau belum login atau data kosong -->
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
        <li class="nav-item">
            <a class="nav-link menu-link {{ request()->routeIs('petakom-instrumen-list') ? 'active' : '' }}"
                href="{{ route('petakom-instrumen-list') }}">
                <i class="ri-file-list-3-line"></i>
                <span data-key="t-widgets">Instrumen</span>
            </a>
        </li>
    @endif
</ul>

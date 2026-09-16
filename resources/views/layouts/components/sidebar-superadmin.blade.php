<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span data-key="t-menu">Superadmin Menu</span></li>
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->is('superadmin/dashboard*') ? 'active' : '' }}"
            href="{{ route('superadmin-dashboard') }}">
            <i class="ri ri-dashboard-line"></i> <span data-key="t-widgets">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->is('superadmin/manajemen-role*') ? 'active' : '' }}"
            href="{{ route('superadmin-manajemen-role') }}">
            <i class="ri ri-key-2-fill"></i> <span data-key="t-widgets">Manajemen Role</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->is('superadmin/manajemen-menu*') ? 'active' : '' }}"
            href="{{ route('superadmin-manajemen-menu') }}">
            <i class="ri ri-list-settings-fill"></i> <span data-key="t-widgets">Manajemen Menu</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->is('manajemen-user*') ? 'active' : '' }}"
            href="{{ route('manajemen-user') }}">
            <i class="ri ri-contacts-fill"></i> <span data-key="t-widgets">Manajemen User</span>
        </a>
    </li>
</ul>

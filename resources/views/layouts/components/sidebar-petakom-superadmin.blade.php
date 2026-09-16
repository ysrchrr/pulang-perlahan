<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span data-key="t-menu">Superadmin Menu</span></li>
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->is('petakom-dashboard') ? 'active' : '' }}"
            href="{{ route('petakom-dashboard') }}">
            <i class="ri ri-dashboard-line"></i> <span data-key="t-widgets">Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->is('petakom-master-instrumen*') ? 'active' : '' }}"
            href="{{ route('petakom-master-instrumen') }}">
            <i class="ri ri-file-list-line"></i> <span data-key="t-widgets">Master Instrumen</span>
        </a>
    </li>
</ul>

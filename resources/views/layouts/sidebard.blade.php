<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }} " href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('medications.*') ? : 'collapsed' }} " href="{{ route('medications.index') }}">
                <i class="bi bi-grid"></i>
                <span>Medicamentos</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('notes.*') ? : 'collapsed' }} " href="{{ route('notes.index') }}">
                <i class="bi bi-grid"></i>
                <span>Notas</span>
            </a>
        </li>

    </ul>

  </aside>

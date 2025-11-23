<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }} " href="/">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.showMedications'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('medications.*') ? '' : 'collapsed' }}" href="{{ route('medications.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Medicamentos</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Notas.showNotes'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('notes.*') ? '' : 'collapsed' }}" href="{{ route('notes.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Notas</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Hábitos.showHabits'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('habits.*') ? '' : 'collapsed' }}" href="{{ route('habits.index') }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Hábitos</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Usuarios.showUsers'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('users.*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Usuarios</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Roles.showRoles'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('roles.*') ? '' : 'collapsed' }}" href="{{ route('roles.index') }}">
                    <i class="bi bi-lock"></i>
                    <span>Roles</span>
                </a>
            </li>
        @endif
    </ul>

  </aside>

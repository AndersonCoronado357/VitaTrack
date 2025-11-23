<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? '' : 'collapsed' }} " href="/">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.showMedications'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('medications.*') ? '' : 'collapsed' }}" href="{{ route('medications.index') }}">
                    <i class="bi bi-capsule"></i>
                    <span>Medicamentos</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Notas.showNotes'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('notes.*') ? '' : 'collapsed' }}" href="{{ route('notes.index') }}">
                    <i class="bi bi-journal-text"></i>
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

        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.showNutrition'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('nutrition.*') ? '' : 'collapsed' }}" href="{{ route('nutrition.index') }}">
                    <i class="bi bi-egg-fried"></i>
                    <span>Nutrición</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.showHealthMetrics'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('health-metrics.*') ? '' : 'collapsed' }}" href="{{ route('health-metrics.index') }}">
                    <i class="bi bi-heart-pulse"></i>
                    <span>Métricas de Salud</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.showSleep'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('sleep.*') ? '' : 'collapsed' }}" href="{{ route('sleep.index') }}">
                    <i class="bi bi-moon-stars"></i>
                    <span>Sueño y Descanso</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.showAppointments'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('appointments.*') ? '' : 'collapsed' }}" href="{{ route('appointments.index') }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Citas y Calendario</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Usuarios.showUsers'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('users.*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Usuarios</span>
                </a>
            </li>
        @endif

        @if (\App\Helpers\RoleHelper::isAuthorized('Roles.showRoles'))
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('roles.*') ? '' : 'collapsed' }}" href="{{ route('roles.index') }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Roles</span>
                </a>
            </li>
        @endif

    </ul>

</aside>

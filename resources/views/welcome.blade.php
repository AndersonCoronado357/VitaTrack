@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Panel de Salud</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Panel Principal</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Bienvenida -->
                    <div class="col-12">
                        <div class="card info-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <div class="card-body">
                                <div class="d-flex align-items-center py-3">
                                    <div class="flex-grow-1">
                                        <h3 class="mb-2">VitaTrack</h3>
                                        <p class="mb-0 opacity-75">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                        </p>
                                        <p class="mb-0 mt-2 opacity-90">
                                            Gestiona tu bienestar de forma integral
                                        </p>
                                    </div>
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: rgba(255,255,255,0.2); width: 80px; height: 80px;">
                                        <i class="bi bi-heart-pulse" style="font-size: 2.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Módulos del sistema -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Módulos Disponibles</h5>
                                <div class="row">
                                    @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.showMedications'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #ffe7f3; color: #dc3545;">
                                                            <i class="bi bi-capsule" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Medicamentos</h5>
                                                            <small class="text-muted">Gestión farmacológica</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Registra y controla tus medicamentos, dosis, horarios y tratamientos activos.</p>
                                                    <a href="{{ route('medications.index') }}" class="btn btn-outline-danger btn-sm">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Notas.showNotes'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #fff3cd; color: #ffc107;">
                                                            <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Notas de Salud</h5>
                                                            <small class="text-muted">Registro clínico</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Documenta síntomas, observaciones y notas importantes sobre tu salud.</p>
                                                    <a href="{{ route('notes.index') }}" class="btn btn-outline-warning btn-sm">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Hábitos.showHabits'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #e7ffe7; color: #28a745;">
                                                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Hábitos Saludables</h5>
                                                            <small class="text-muted">Seguimiento de rutinas</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Crea y monitorea hábitos diarios con seguimiento de rachas y estadísticas.</p>
                                                    <a href="{{ route('habits.index') }}" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.showNutrition'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #d4edda; color: #155724;">
                                                            <i class="bi bi-egg-fried" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Nutrición y Dieta</h5>
                                                            <small class="text-muted">Control alimenticio</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Registra comidas, calorías, macronutrientes y alcanza tus metas nutricionales.</p>
                                                    <a href="{{ route('nutrition.index') }}" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.showHealthMetrics'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #cfe2ff; color: #084298;">
                                                            <i class="bi bi-heart-pulse" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Métricas de Salud</h5>
                                                            <small class="text-muted">Monitoreo vital</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Registra presión arterial, glucosa, peso y otras métricas con alertas personalizadas.</p>
                                                    <a href="{{ route('health-metrics.index') }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.showSleep'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #f3e7ff; color: #6f42c1;">
                                                            <i class="bi bi-moon-stars" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Sueño y Descanso</h5>
                                                            <small class="text-muted">Calidad del sueño</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Registra horas de sueño, calidad y establece metas para un mejor descanso.</p>
                                                    <a href="{{ route('sleep.index') }}" class="btn btn-outline-purple btn-sm" style="border-color: #6f42c1; color: #6f42c1;">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.showAppointments'))
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                             style="width: 60px; height: 60px; background-color: #e7f3ff; color: #0d6efd;">
                                                            <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-0">Citas y Calendario</h5>
                                                            <small class="text-muted">Agenda médica</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-3">Programa citas médicas, eventos y recibe recordatorios en el calendario.</p>
                                                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-arrow-right-circle"></i> Ir al módulo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del sistema -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Sobre el Sistema</h5>
                                <p>
                                    Este sistema integral te permite gestionar todos los aspectos de tu salud personal en un solo lugar.
                                    Mantén un registro completo y organizado de tu información médica, hábitos, nutrición y bienestar general.
                                </p>
                                <div class="row mt-4">
                                    <div class="col-md-4 text-center mb-3">
                                        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                                        <h6 class="mt-2">Seguro</h6>
                                        <small class="text-muted">Tus datos están protegidos</small>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <i class="bi bi-graph-up-arrow text-primary" style="font-size: 3rem;"></i>
                                        <h6 class="mt-2">Estadísticas</h6>
                                        <small class="text-muted">Visualiza tu progreso</small>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <i class="bi bi-bell text-warning" style="font-size: 3rem;"></i>
                                        <h6 class="mt-2">Recordatorios</h6>
                                        <small class="text-muted">No olvides nada importante</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-lg-4">
                <!-- Accesos rápidos -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Accesos Rápidos</h5>
                        <div class="d-grid gap-2">
                            @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.createAppointments'))
                                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                    <i class="bi bi-calendar-plus me-2"></i> Nueva Cita
                                </a>
                            @endif
                            @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.createMedication'))
                                <a href="{{ route('medications.create') }}" class="btn btn-danger">
                                    <i class="bi bi-capsule-pill me-2"></i> Registrar Medicamento
                                </a>
                            @endif
                            @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.createNutrition'))
                                <a href="{{ route('nutrition.create') }}" class="btn btn-success">
                                    <i class="bi bi-egg-fried me-2"></i> Registrar Comida
                                </a>
                            @endif
                            @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.createSleep'))
                                <a href="{{ route('sleep.create') }}" class="btn btn-info">
                                    <i class="bi bi-moon-stars me-2"></i> Registrar Sueño
                                </a>
                            @endif
                            @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.createHealthMetrics'))
                                <a href="{{ route('health-metrics.create') }}" class="btn btn-warning">
                                    <i class="bi bi-heart-pulse me-2"></i> Registrar Métrica
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Consejos de salud -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-lightbulb text-warning"></i> Consejos de Salud
                        </h5>
                        <div class="news">
                            <div class="post-item clearfix mb-3 pb-3 border-bottom">
                                <i class="bi bi-droplet text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Hidratación</h6>
                                <p><small>Bebe al menos 8 vasos de agua al día para mantener tu cuerpo hidratado.</small></p>
                            </div>

                            <div class="post-item clearfix mb-3 pb-3 border-bottom">
                                <i class="bi bi-moon-stars text-info" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Sueño de Calidad</h6>
                                <p><small>Duerme entre 7-9 horas diarias para una óptima recuperación física y mental.</small></p>
                            </div>

                            <div class="post-item clearfix mb-3 pb-3 border-bottom">
                                <i class="bi bi-person-walking text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Actividad Física</h6>
                                <p><small>Realiza al menos 30 minutos de ejercicio moderado 5 días a la semana.</small></p>
                            </div>

                            <div class="post-item clearfix mb-3">
                                <i class="bi bi-heart-pulse text-danger" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Chequeos Regulares</h6>
                                <p><small>Visita a tu médico regularmente para prevenir y detectar a tiempo cualquier problema.</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Funcionalidades destacadas -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-star text-warning"></i> Funcionalidades Destacadas
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Seguimiento de medicamentos y recordatorios
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Control nutricional con cálculo de macros
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Monitoreo de métricas vitales
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Calendario de citas médicas
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Seguimiento de hábitos saludables
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Registro de calidad del sueño
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

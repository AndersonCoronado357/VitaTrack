@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Sueño y Descanso</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Sueño y Descanso</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Navegación de semanas -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-3">
                    <div class="col-md-6">
                        <form action="{{ route('sleep.index') }}" method="GET" id="weekForm">
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeWeek(-7)">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <input type="date"
                                       class="form-control text-center"
                                       name="date"
                                       value="{{ $currentDate }}"
                                       onchange="document.getElementById('weekForm').submit()">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeWeek(7)">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </form>
                        <small class="text-muted">
                            Semana del {{ $startOfWeek->format('d/m/Y') }} al {{ $endOfWeek->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="col-md-6 text-end mt-2 mt-md-0">
                        @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.goalsSleep'))
                            <a href="{{ route('sleep.goals') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-bullseye"></i> Metas
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.statsSleep'))
                            <a href="{{ route('sleep.statistics') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-graph-up"></i> Estadísticas
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.historySleep'))
                            <a href="{{ route('sleep.history') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-clock-history"></i> Historial
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.createSleep'))
                            <a href="{{ route('sleep.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Registrar Sueño
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de la semana -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio de Horas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #6f42c1; color: white;">
                                <i class="bi bi-moon-stars"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $weekStats['avg_hours'] ? number_format($weekStats['avg_hours'], 1) : 'N/A' }}h</h6>
                                <span class="text-muted small">esta semana</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Noches Registradas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #4bc0c0; color: white;">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $weekStats['total_nights'] }}/7</h6>
                                <span class="text-muted small">noches</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Meta Alcanzada</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #28a745; color: white;">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $weekStats['nights_met_goal'] }}</h6>
                                <span class="text-muted small">noches</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Interrupciones</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ff6384; color: white;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $weekStats['avg_interruptions'] ? number_format($weekStats['avg_interruptions'], 1) : '0' }}</h6>
                                <span class="text-muted small">promedio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registros de la semana -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Registros de la Semana</h5>

                @if($records->count() > 0)
                    <div class="row">
                        @foreach($records as $record)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 sleep-card" style="border-left: 4px solid var(--bs-{{ $record->quality_color }});">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-0">{{ $record->sleep_date->format('l') }}</h6>
                                                <small class="text-muted">{{ $record->sleep_date->format('d/m/Y') }}</small>
                                            </div>
                                            <span class="badge bg-{{ $record->quality_color }}">
                                                <i class="{{ $record->quality_icon }}"></i> {{ $record->quality_text }}
                                            </span>
                                        </div>

                                        <div class="sleep-info mt-3">
                                            <div class="mb-2">
                                                <i class="bi bi-moon me-2 text-primary"></i>
                                                <strong>{{ substr($record->bedtime, 0, 5) }}</strong>
                                                <i class="bi bi-arrow-right mx-2"></i>
                                                <i class="bi bi-sunrise me-2 text-warning"></i>
                                                <strong>{{ substr($record->wake_time, 0, 5) }}</strong>
                                            </div>

                                            <div class="mb-2">
                                                <i class="bi bi-clock-history me-2"></i>
                                                <strong>{{ number_format($record->total_hours, 1) }} horas</strong>
                                                @if($record->total_hours >= $goal->target_hours - 0.5)
                                                    <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                @endif
                                            </div>

                                            @if($record->interruptions > 0)
                                                <div class="mb-2">
                                                    <i class="bi bi-exclamation-circle me-2 text-warning"></i>
                                                    {{ $record->interruptions }} interrupción(es)
                                                </div>
                                            @endif

                                            @if($record->felt_rested)
                                                <div class="mb-2">
                                                    <i class="bi bi-emoji-smile me-2 text-success"></i>
                                                    Se sintió descansado/a
                                                </div>
                                            @endif

                                            @if($record->notes)
                                                <div class="mt-2">
                                                    <small class="text-muted">{{ Str::limit($record->notes, 60) }}</small>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.updateSleep'))
                                                <a href="{{ route('sleep.edit', $record->id) }}" class="btn btn-warning btn-sm flex-fill">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                            @endif
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.deleteSleep'))
                                                <form action="{{ route('sleep.delete', $record->id) }}" method="POST" style="flex: 1;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm w-100 btnDelete">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-moon-stars" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay registros para esta semana</h4>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.createSleep'))
                            <p class="text-muted">Comienza a registrar tus horas de sueño</p>
                            <a href="{{ route('sleep.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Registrar Sueño
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

<style>
.sleep-card {
    transition: all 0.3s ease;
}

.sleep-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.sleep-info i {
    width: 20px;
}
</style>

<script type="module">
function changeWeek(days) {
    const dateInput = document.querySelector('input[name="date"]');
    const currentDate = new Date(dateInput.value);
    currentDate.setDate(currentDate.getDate() + days);
    dateInput.value = currentDate.toISOString().split('T')[0];
    document.getElementById('weekForm').submit();
}

window.changeWeek = changeWeek;

$(document).ready(function() {
    $('.btnDelete').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Eliminar registro?',
            text: 'Esta acción no se puede revertir',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('form').submit();
            }
        });
    });
});
</script>

@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Historial de Sueño</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sleep.index') }}">Sueño y Descanso</a></li>
                <li class="breadcrumb-item active">Historial</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Filtros -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-2">
                    <div class="col-md-8">
                        <form action="{{ route('sleep.history') }}" method="GET" class="row g-2">
                            <div class="col-auto">
                                <label class="form-label">Período:</label>
                            </div>
                            <div class="col-auto">
                                <select name="period" class="form-select" onchange="this.form.submit()">
                                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Última semana</option>
                                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Último mes</option>
                                    <option value="3months" {{ $period == '3months' ? 'selected' : '' }}>Últimos 3 meses</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('sleep.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas del período -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #6f42c1; color: white;">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['avg_hours'] ? number_format($stats['avg_hours'], 1) : 'N/A' }}h</h6>
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
                                <h6>{{ $stats['nights_met_goal'] }}/{{ $stats['total_nights'] }}</h6>
                                <span class="text-muted small">
                                    {{ $stats['total_nights'] > 0 ? round(($stats['nights_met_goal'] / $stats['total_nights']) * 100) : 0 }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Descansado</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #17a2b8; color: white;">
                                <i class="bi bi-emoji-smile"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['felt_rested_count'] }}</h6>
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
                                <i class="bi bi-exclamation-circle"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['avg_interruptions'] ? number_format($stats['avg_interruptions'], 1) : '0' }}</h6>
                                <span class="text-muted small">promedio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de registros -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Registros Detallados</h5>

                @if($records->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Horario</th>
                                    <th>Total Horas</th>
                                    <th>Calidad</th>
                                    <th>Interrupciones</th>
                                    <th>Descansado</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td>
                                            <strong>{{ $record->sleep_date->format('d/m/Y') }}</strong>
                                            <br><small class="text-muted">{{ $record->sleep_date->format('l') }}</small>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="bi bi-moon"></i> {{ substr($record->bedtime, 0, 5) }}
                                                <br>
                                                <i class="bi bi-sunrise"></i> {{ substr($record->wake_time, 0, 5) }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ number_format($record->total_hours, 1) }}h</strong>
                                            @if($record->total_hours >= $goal->target_hours - 0.5)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $record->quality_color }}">
                                                <i class="{{ $record->quality_icon }}"></i>
                                                {{ $record->quality_text }}
                                            </span>
                                        </td>
                                        <td>{{ $record->interruptions }}</td>
                                        <td>
                                            @if($record->felt_rested)
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->notes)
                                                <small>{{ Str::limit($record->notes, 30) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Sueño y Descanso.updateSleep'))
                                                <a href="{{ route('sleep.edit', $record->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $records->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <p class="text-center text-muted py-4">No hay registros para este período</p>
                @endif
            </div>
        </div>
    </section>
@endsection

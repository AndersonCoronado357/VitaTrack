@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Métricas de Salud</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Métricas de Salud</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Alertas recientes -->
        @if($recentAlerts > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Tienes <strong>{{ $recentAlerts }}</strong> registro(s) con valores fuera del rango normal en los últimos 7 días.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filtros y acciones -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-3">
                    <div class="col-md-6">
                        <form action="{{ route('health-metrics.index') }}" method="GET">
                            <div class="row g-2">
                                <div class="col-auto">
                                    <label class="form-label">Filtrar por métrica:</label>
                                </div>
                                <div class="col-auto">
                                    <select name="metric_type" class="form-select" onchange="this.form.submit()">
                                        <option value="all" {{ $metricType == 'all' ? 'selected' : '' }}>Todas</option>
                                        <option value="blood_pressure" {{ $metricType == 'blood_pressure' ? 'selected' : '' }}>Presión Arterial</option>
                                        <option value="glucose" {{ $metricType == 'glucose' ? 'selected' : '' }}>Glucosa</option>
                                        <option value="weight" {{ $metricType == 'weight' ? 'selected' : '' }}>Peso</option>
                                        <option value="heart_rate" {{ $metricType == 'heart_rate' ? 'selected' : '' }}>Frecuencia Cardíaca</option>
                                        <option value="temperature" {{ $metricType == 'temperature' ? 'selected' : '' }}>Temperatura</option>
                                        <option value="oxygen" {{ $metricType == 'oxygen' ? 'selected' : '' }}>Oxígeno</option>
                                        <option value="cholesterol" {{ $metricType == 'cholesterol' ? 'selected' : '' }}>Colesterol</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end mt-2 mt-md-0">
                        @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.rangesHealthMetrics'))
                            <a href="{{ route('health-metrics.ranges') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-sliders"></i> Rangos
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.statsHealthMetrics'))
                            <a href="{{ route('health-metrics.statistics', ['metric_type' => $metricType != 'all' ? $metricType : 'blood_pressure']) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-graph-up"></i> Estadísticas
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.createHealthMetrics'))
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-plus-circle"></i> Registrar Métrica
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'blood_pressure']) }}">
                                        <i class="bi bi-heart-pulse text-danger"></i> Presión Arterial
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'glucose']) }}">
                                        <i class="bi bi-droplet-half text-warning"></i> Glucosa
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'weight']) }}">
                                        <i class="bi bi-speedometer2 text-primary"></i> Peso
                                    </a></li>
                                    <li><a霜 class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'heart_rate']) }}">
                                        <i class="bi bi-activity text-danger"></i> Frecuencia Cardíaca
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'temperature']) }}">
                                        <i class="bi bi-thermometer-half text-danger"></i> Temperatura
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'oxygen']) }}">
                                        <i class="bi bi-lungs text-info"></i> Oxígeno
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('health-metrics.create', ['metric_type' => 'cholesterol']) }}">
                                        <i class="bi bi-clipboard-pulse text-warning"></i> Colesterol
                                    </a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de métricas -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Registros Recientes</h5>

                @if($metrics->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Métrica</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($metrics as $metric)
                                    <tr>
                                        <td>
                                            <strong>{{ $metric->measured_date->format('d/m/Y') }}</strong>
                                            @if($metric->measured_time)
                                                <br><small class="text-muted">{{ substr($metric->measured_time, 0, 5) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="{{ $metric->metric_type_icon }} me-1"></i>
                                            {{ $metric->metric_type_name }}
                                            @if($metric->metric_type === 'glucose' && $metric->is_fasting)
                                                <br><small class="badge bg-info">En ayunas</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-{{ $metric->status_color }}">
                                                {{ $metric->formatted_value }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $metric->status_color }}">
                                                @if($metric->status === 'alert')
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                @elseif($metric->status === 'warning')
                                                    <i class="bi bi-exclamation-circle"></i>
                                                @else
                                                    <i class="bi bi-check-circle"></i>
                                                @endif
                                                {{ $metric->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($metric->notes)
                                                <small>{{ Str::limit($metric->notes, 40) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.updateHealthMetrics'))
                                                <a href="{{ route('health-metrics.edit', $metric->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.deleteHealthMetrics'))
                                                <form action="{{ route('health-metrics.delete', $metric->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $metrics->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-data" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay métricas registradas</h4>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Métricas de Salud.createHealthMetrics'))
                            <p class="text-muted">Comienza a registrar tus métricas de salud</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

<script type="module">
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

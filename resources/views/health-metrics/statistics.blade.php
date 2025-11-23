@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Estadísticas de Métricas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('health-metrics.index') }}">Métricas de Salud</a></li>
                <li class="breadcrumb-item active">Estadísticas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Filtros -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-2">
                    <div class="col-md-8">
                        <form action="{{ route('health-metrics.statistics') }}" method="GET" class="row g-2">
                            <div class="col-auto">
                                <label class="form-label">Métrica:</label>
                            </div>
                            <div class="col-auto">
                                <select name="metric_type" class="form-select" onchange="this.form.submit()">
                                    <option value="blood_pressure" {{ $metricType == 'blood_pressure' ? 'selected' : '' }}>Presión Arterial</option>
                                    <option value="glucose" {{ $metricType == 'glucose' ? 'selected' : '' }}>Glucosa</option>
                                    <option value="weight" {{ $metricType == 'weight' ? 'selected' : '' }}>Peso</option>
                                    <option value="heart_rate" {{ $metricType == 'heart_rate' ? 'selected' : '' }}>Frecuencia Cardíaca</option>
                                    <option value="temperature" {{ $metricType == 'temperature' ? 'selected' : '' }}>Temperatura</option>
                                    <option value="oxygen" {{ $metricType == 'oxygen' ? 'selected' : '' }}>Oxígeno</option>
                                    <option value="cholesterol" {{ $metricType == 'cholesterol' ? 'selected' : '' }}>Colesterol</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Período:</label>
                            </div>
                            <div class="col-auto">
                                <select name="days" class="form-select" onchange="this.form.submit()">
                                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 días</option>
                                    <option value="14" {{ $days == 14 ? 'selected' : '' }}>14 días</option>
                                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 días</option>
                                    <option value="60" {{ $days == 60 ? 'selected' : '' }}>60 días</option>
                                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 días</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('health-metrics.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #4bc0c0; color: white;">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['avg'] ? number_format($stats['avg'], 1) : 'N/A' }}</h6>
                                <span class="text-muted small">
                                    @php
                                        $metric = \App\Models\HealthMetric::where('metric_type', $metricType)->first();
                                    @endphp
                                    {{ $metric->unit ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Mínimo</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #36a2eb; color: white;">
                                <i class="bi bi-arrow-down"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['min'] ? number_format($stats['min'], 1) : 'N/A' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Máximo</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ff6384; color: white;">
                                <i class="bi bi-arrow-up"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['max'] ? number_format($stats['max'], 1) : 'N/A' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Registros</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ffce56; color: white;">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $stats['count'] }}</h6>
                                <span class="text-muted small">mediciones</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if($stats['alerts'] > 0 || $stats['warnings'] > 0)
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>{{ $stats['alerts'] }}</strong> registro(s) en estado de alerta
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>{{ $stats['warnings'] }}</strong> registro(s) requieren atención
                    </div>
                </div>
            </div>
        @endif

        <!-- Gráfica de evolución -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Evolución Temporal</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="metricsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabla de datos -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Datos Detallados</h5>
                @if($metrics->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($metrics as $metric)
                                    <tr>
                                        <td>{{ $metric->measured_date->format('d/m/Y') }}</td>
                                        <td>{{ $metric->measured_time ? substr($metric->measured_time, 0, 5) : '-' }}</td>
                                        <td>
                                            <strong class="text-{{ $metric->status_color }}">
                                                {{ $metric->formatted_value }}
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $metric->status_color }}">
                                                {{ $metric->status_text }}
                                            </span>
                                        </td>
                                        <td>{{ $metric->notes ? Str::limit($metric->notes, 40) : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">No hay datos para el período seleccionado</p>
                @endif
            </div>
        </div>
    </section>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const metrics = @json($metrics);
    const ranges = @json($ranges);
    const metricType = '{{ $metricType }}';

    if (metrics.length === 0) {
        return;
    }

    // Preparar datos
    const dates = metrics.map(m => {
        const date = new Date(m.measured_date);
        return date.getDate() + '/' + (date.getMonth() + 1);
    });

    const values = metrics.map(m => parseFloat(m.value));
    const secondaryValues = metrics.map(m => m.value_secondary ? parseFloat(m.value_secondary) : null);

    // Configuración de colores según estado
    const backgroundColors = metrics.map(m => {
        if (m.status === 'alert') return '#ff638440';
        if (m.status === 'warning') return '#ffce5640';
        return '#4bc0c040';
    });

    const borderColors = metrics.map(m => {
        if (m.status === 'alert') return '#ff6384';
        if (m.status === 'warning') return '#ffce56';
        return '#4bc0c0';
    });

    const ctx = document.getElementById('metricsChart').getContext('2d');

    const datasets = [
        {
            label: metricType === 'blood_pressure' ? 'Sistólica' : 'Valor',
            data: values,
            borderColor: borderColors,
            backgroundColor: backgroundColors,
            fill: false,
            tension: 0.4,
            pointRadius: 5,
            pointHoverRadius: 7
        }
    ];

    // Líneas de rangos
    if (ranges.min_normal && ranges.max_normal) {
        datasets.push({
            label: 'Rango normal superior',
            data: Array(dates.length).fill(parseFloat(ranges.max_normal)),
            borderColor: '#28a745',
            borderDash: [5, 5],
            fill: false,
            pointRadius: 0
        });

        datasets.push({
            label: 'Rango normal inferior',
            data: Array(dates.length).fill(parseFloat(ranges.min_normal)),
            borderColor: '#28a745',
            borderDash: [5, 5],
            fill: false,
            pointRadius: 0
        });
    }

    // Para presión arterial, agregar línea diastólica
    if (metricType === 'blood_pressure' && secondaryValues.some(v => v !== null)) {
        datasets.push({
            label: 'Diastólica',
            data: secondaryValues,
            borderColor: '#36a2eb',
            backgroundColor: '#36a2eb40',
            fill: false,
            tension: 0.4,
            pointRadius: 5
        });
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toFixed(1);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(0);
                        }
                    }
                }
            }
        }
    });
});
</script>

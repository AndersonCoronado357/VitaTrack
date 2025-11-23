@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Estadísticas de Sueño</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sleep.index') }}">Sueño y Descanso</a></li>
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
                        <form action="{{ route('sleep.statistics') }}" method="GET" class="row g-2">
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
                        <a href="{{ route('sleep.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas principales -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #6f42c1; color: white;">
                                <i class="bi bi-moon-stars"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($stats['avg_hours'], 1) }}h</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Mejor Sueño</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #28a745; color: white;">
                                <i class="bi bi-arrow-up"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($stats['best_sleep'], 1) }}h</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Peor Sueño</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #dc3545; color: white;">
                                <i class="bi bi-arrow-down"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($stats['worst_sleep'], 1) }}h</h6>
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
                                <h6>{{ number_format($stats['felt_rested_percent'], 0) }}%</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de horas de sueño -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Evolución de Horas de Sueño</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="sleepChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución de calidad -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Distribución de Calidad del Sueño</h5>
                        <canvas id="qualityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resumen de Calidad</h5>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="bi bi-star-fill text-success"></i> Excelente</span>
                                <strong>{{ $qualityStats['excellent'] }} noches ({{ $qualityStats['total'] > 0 ? round(($qualityStats['excellent'] / $qualityStats['total']) * 100) : 0 }}%)</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="bi bi-emoji-smile text-primary"></i> Buena</span>
                                <strong>{{ $qualityStats['good'] }} noches ({{ $qualityStats['total'] > 0 ? round(($qualityStats['good'] / $qualityStats['total']) * 100) : 0 }}%)</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="bi bi-emoji-neutral text-warning"></i> Regular</span>
                                <strong>{{ $qualityStats['fair'] }} noches ({{ $qualityStats['total'] > 0 ? round(($qualityStats['fair'] / $qualityStats['total']) * 100) : 0 }}%)</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="bi bi-emoji-frown text-danger"></i> Mala</span>
                                <strong>{{ $qualityStats['poor'] }} noches ({{ $qualityStats['total'] > 0 ? round(($qualityStats['poor'] / $qualityStats['total']) * 100) : 0 }}%)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const records = @json($records);
    const goal = @json($goal);

    // Preparar datos
    const dates = records.map(r => {
        const date = new Date(r.sleep_date);
        return date.getDate() + '/' + (date.getMonth() + 1);
    });

    const hours = records.map(r => parseFloat(r.total_hours));
    const interruptions = records.map(r => r.interruptions);

    // Colores según calidad
    const backgroundColors = records.map(r => {
        if (r.quality === 'excellent') return '#28a74540';
        if (r.quality === 'good') return '#007bff40';
        if (r.quality === 'fair') return '#ffc10740';
        return '#dc354540';
    });

    const borderColors = records.map(r => {
        if (r.quality === 'excellent') return '#28a745';
        if (r.quality === 'good') return '#007bff';
        if (r.quality === 'fair') return '#ffc107';
        return '#dc3545';
    });

    // Gráfica de horas de sueño
    const ctx = document.getElementById('sleepChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Horas de sueño',
                    data: hours,
                    borderColor: borderColors,
                    backgroundColor: backgroundColors,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: 'Meta',
                    data: Array(dates.length).fill(parseFloat(goal.target_hours)),
                    borderColor: '#28a745',
                    borderDash: [5, 5],
                    fill: false,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label === 'Horas de sueño') {
                                const index = context.dataIndex;
                                const record = records[index];
                                return [
                                    `Horas: ${context.parsed.y.toFixed(1)}`,
                                    `Calidad: ${record.quality_text}`,
                                    `Interrupciones: ${record.interruptions}`
                                ];
                            }
                            return `Meta: ${context.parsed.y}h`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0,
                    max: 12
                }
            }
        }
    });

    // Gráfica de calidad
    const qualityStats = @json($qualityStats);
    const ctxQuality = document.getElementById('qualityChart').getContext('2d');
    new Chart(ctxQuality, {
        type: 'doughnut',
        data: {
            labels: ['Excelente', 'Buena', 'Regular', 'Mala'],
            datasets: [{
                data: [
                    qualityStats.excellent,
                    qualityStats.good,
                    qualityStats.fair,
                    qualityStats.poor
                ],
                backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

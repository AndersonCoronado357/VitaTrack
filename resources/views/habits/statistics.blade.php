@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Estadísticas del Hábito</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('habits.index') }}">Hábitos</a></li>
                <li class="breadcrumb-item active">Estadísticas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- Información del hábito -->
            <div class="col-md-12">
                <div class="card" style="border-left: 4px solid {{ $habit->color }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="habit-icon me-3" style="background-color: {{ $habit->color }}20; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                                <i class="{{ $habit->icon }}" style="color: {{ $habit->color }}; font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $habit->name }}</h3>
                                <p class="text-muted mb-0">{{ $habit->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas principales -->
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-fire text-warning" style="font-size: 2rem;"></i>
                        <h3 class="mt-2">{{ $stats['current_streak'] }}</h3>
                        <p class="text-muted mb-0">Racha Actual</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-trophy text-warning" style="font-size: 2rem;"></i>
                        <h3 class="mt-2">{{ $stats['best_streak'] }}</h3>
                        <p class="text-muted mb-0">Mejor Racha</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-calendar-check text-success" style="font-size: 2rem;"></i>
                        <h3 class="mt-2">{{ $stats['completion_rate_7'] }}%</h3>
                        <p class="text-muted mb-0">Últimos 7 días</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                        <h3 class="mt-2">{{ $stats['completion_rate_30'] }}%</h3>
                        <p class="text-muted mb-0">Últimos 30 días</p>
                    </div>
                </div>
            </div>

            <!-- Gráfica de los últimos 30 días -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Progreso de los últimos 30 días</h5>
                        <div style="position: relative; height: 400px;">
                            <canvas id="habitChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial reciente -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Historial de Cumplimiento (últimos 30 días)</h5>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Progreso</th>
                                        <th>Estado</th>
                                        <th>Notas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($log->completion_date)->format('d/m/Y') }}</td>
                                            <td>{{ $log->count }}/{{ $habit->goal_count }}</td>
                                            <td>
                                                @if($log->count >= $habit->goal_count)
                                                    <span class="badge bg-success">Completado</span>
                                                @else
                                                    <span class="badge bg-warning">Parcial</span>
                                                @endif
                                            </td>
                                            <td>{{ $log->notes ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No hay registros en los últimos 30 días</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('habits.index') }}" class="btn btn-secondary">Volver a Hábitos</a>
        </div>
    </section>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos pasados directamente desde PHP
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);
    const goalCount = {{ $habit->goal_count }};
    const habitColor = '{{ $habit->color }}';

    console.log('Chart Labels:', chartLabels);
    console.log('Chart Data:', chartData);
    console.log('Goal Count:', goalCount);

    const ctx = document.getElementById('habitChart').getContext('2d');

    // Crear colores dinámicos para cada barra
    const backgroundColors = chartData.map(count => {
        if (count >= goalCount) {
            return '#28a745'; // Verde - Meta alcanzada
        } else if (count > 0) {
            return habitColor + '99'; // Color del hábito con transparencia - Progreso parcial
        } else {
            return '#e9ecef'; // Gris - Sin progreso
        }
    });

    const borderColors = chartData.map(count => {
        if (count >= goalCount) {
            return '#1e7e34';
        } else if (count > 0) {
            return habitColor;
        } else {
            return '#dee2e6';
        }
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Progreso',
                    data: chartData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 4
                },
                {
                    label: 'Meta',
                    data: Array(30).fill(goalCount),
                    type: 'line',
                    borderColor: '#dc3545',
                    borderDash: [8, 4],
                    fill: false,
                    pointRadius: 0,
                    borderWidth: 2,
                    tension: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label === 'Progreso') {
                                const value = context.parsed.y;
                                const goal = goalCount;
                                const percentage = goal > 0 ? Math.round((value / goal) * 100) : 0;

                                let status = '';
                                if (value >= goal) {
                                    status = ' ✓ Completado';
                                } else if (value > 0) {
                                    status = ' - Parcial';
                                } else {
                                    status = ' - Sin registro';
                                }

                                return `Progreso: ${value}/${goal} (${percentage}%)${status}`;
                            } else {
                                return `Meta diaria: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 11
                        }
                    },
                    title: {
                        display: true,
                        text: 'Veces completado',
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        padding: 10
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        font: {
                            size: 10
                        }
                    },
                    title: {
                        display: true,
                        text: 'Últimos 30 días',
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        padding: 10
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>

<style>
#habitChart {
    max-height: 400px;
}
</style>

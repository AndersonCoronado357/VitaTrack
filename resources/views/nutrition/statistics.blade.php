@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Estadísticas Nutricionales</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('nutrition.index') }}">Nutrición</a></li>
                <li class="breadcrumb-item active">Estadísticas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Selector de período -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-2">
                    <div class="col-md-8">
                        <form action="{{ route('nutrition.statistics') }}" method="GET" class="row g-2">
                            <div class="col-auto">
                                <label class="form-label">Período:</label>
                            </div>
                            <div class="col-auto">
                                <select name="days" class="form-select" onchange="this.form.submit()">
                                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Últimos 7 días</option>
                                    <option value="14" {{ $days == 14 ? 'selected' : '' }}>Últimos 14 días</option>
                                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Últimos 30 días</option>
                                    <option value="60" {{ $days == 60 ? 'selected' : '' }}>Últimos 60 días</option>
                                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Últimos 90 días</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('nutrition.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promedios -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio Calorías</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ff6384; color: white;">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($averages->avg_calories ?? 0, 0) }} kcal</h6>
                                <span class="text-muted small">por día</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio Proteínas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #36a2eb; color: white;">
                                <i class="bi bi-egg"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($averages->avg_proteins ?? 0, 1) }} g</h6>
                                <span class="text-muted small">por día</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio Carbohidratos</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ffce56; color: white;">
                                <i class="bi bi-bread-slice"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($averages->avg_carbs ?? 0, 1) }} g</h6>
                                <span class="text-muted small">por día</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Promedio Grasas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #4bc0c0; color: white;">
                                <i class="bi bi-droplet-fill"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($averages->avg_fats ?? 0, 1) }} g</h6>
                                <span class="text-muted small">por día</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de calorías diarias -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Calorías Diarias</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="caloriesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de macronutrientes -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Macronutrientes Diarios</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="macrosChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por tipo de comida -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Distribución por Tipo de Comida</h5>
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <canvas id="mealTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($dailyData);
    const goal = @json($goal);
    const mealTypeDistribution = @json($mealTypeDistribution);

    // Preparar datos para gráficas
    const dates = dailyData.map(d => {
        const date = new Date(d.meal_date);
        return date.getDate() + '/' + (date.getMonth() + 1);
    });
    const calories = dailyData.map(d => d.total_calories);
    const proteins = dailyData.map(d => d.total_proteins);
    const carbs = dailyData.map(d => d.total_carbs);
    const fats = dailyData.map(d => d.total_fats);

    // Gráfica de calorías
    const ctxCalories = document.getElementById('caloriesChart').getContext('2d');
    new Chart(ctxCalories, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Calorías consumidas',
                    data: calories,
                    borderColor: '#ff6384',
                    backgroundColor: '#ff638440',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Meta diaria',
                    data: Array(dates.length).fill(goal.daily_calories_goal),
                    borderColor: '#36a2eb',
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
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfica de macronutrientes
    const ctxMacros = document.getElementById('macrosChart').getContext('2d');
    new Chart(ctxMacros, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Proteínas (g)',
                    data: proteins,
                    borderColor: '#36a2eb',
                    backgroundColor: '#36a2eb40',
                    fill: false
                },
                {
                    label: 'Carbohidratos (g)',
                    data: carbs,
                    borderColor: '#ffce56',
                    backgroundColor: '#ffce5640',
                    fill: false
                },
                {
                    label: 'Grasas (g)',
                    data: fats,
                    borderColor: '#4bc0c0',
                    backgroundColor: '#4bc0c040',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfica de distribución por tipo de comida
    const mealTypeLabels = {
        'breakfast': '🌅 Desayuno',
        'lunch': '☀️ Almuerzo',
        'dinner': '🌙 Cena',
        'snack': '🍎 Merienda'
    };

    const mealTypeColors = {
        'breakfast': '#ffce56',
        'lunch': '#36a2eb',
        'dinner': '#4bc0c0',
        'snack': '#ff6384'
    };

    const mealLabels = mealTypeDistribution.map(m => mealTypeLabels[m.meal_type] || m.meal_type);
    const mealCalories = mealTypeDistribution.map(m => m.total_calories);
    const mealColors = mealTypeDistribution.map(m => mealTypeColors[m.meal_type] || '#cccccc');

    const ctxMealType = document.getElementById('mealTypeChart').getContext('2d');
    new Chart(ctxMealType, {
        type: 'doughnut',
        data: {
            labels: mealLabels,
            datasets: [{
                data: mealCalories,
                backgroundColor: mealColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} kcal (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>

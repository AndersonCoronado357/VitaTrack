@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Nutrición y Dieta</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Nutrición</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Selector de fecha y acciones -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-3">
                    <div class="col-md-4">
                        <form action="{{ route('nutrition.index') }}" method="GET" id="dateForm">
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeDate(-1)">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <input type="date"
                                       class="form-control text-center"
                                       name="date"
                                       value="{{ $date }}"
                                       onchange="document.getElementById('dateForm').submit()">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeDate(1)">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8 text-end mt-2 mt-md-0">
                        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.goalsNutrition'))
                            <a href="{{ route('nutrition.goals') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-bullseye"></i> Metas
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.statsNutrition'))
                            <a href="{{ route('nutrition.statistics') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-graph-up"></i> Estadísticas
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.historyNutrition'))
                            <a href="{{ route('nutrition.history') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-clock-history"></i> Historial
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen del día -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Calorías</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ff6384; color: white;">
                                <i class="bi bi-fire"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $totals->total_calories ?? 0 }} / {{ $goal->daily_calories_goal }}</h6>
                                <span class="text-muted small pt-1">kcal</span>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            @php
                                $caloriesPercent = $goal->daily_calories_goal > 0
                                    ? min(($totals->total_calories / $goal->daily_calories_goal) * 100, 100)
                                    : 0;
                            @endphp
                            <div class="progress-bar" style="width: {{ $caloriesPercent }}%; background-color: #ff6384;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Proteínas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #36a2eb; color: white;">
                                <i class="bi bi-egg"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($totals->total_proteins ?? 0, 1) }} / {{ $goal->daily_proteins_goal }}</h6>
                                <span class="text-muted small pt-1">g</span>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            @php
                                $proteinsPercent = $goal->daily_proteins_goal > 0
                                    ? min(($totals->total_proteins / $goal->daily_proteins_goal) * 100, 100)
                                    : 0;
                            @endphp
                            <div class="progress-bar" style="width: {{ $proteinsPercent }}%; background-color: #36a2eb;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Carbohidratos</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #ffce56; color: white;">
                                <i class="bi bi-bread-slice"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($totals->total_carbs ?? 0, 1) }} / {{ $goal->daily_carbs_goal }}</h6>
                                <span class="text-muted small pt-1">g</span>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            @php
                                $carbsPercent = $goal->daily_carbs_goal > 0
                                    ? min(($totals->total_carbs / $goal->daily_carbs_goal) * 100, 100)
                                    : 0;
                            @endphp
                            <div class="progress-bar" style="width: {{ $carbsPercent }}%; background-color: #ffce56;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="card-title">Grasas</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #4bc0c0; color: white;">
                                <i class="bi bi-droplet-fill"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ number_format($totals->total_fats ?? 0, 1) }} / {{ $goal->daily_fats_goal }}</h6>
                                <span class="text-muted small pt-1">g</span>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            @php
                                $fatsPercent = $goal->daily_fats_goal > 0
                                    ? min(($totals->total_fats / $goal->daily_fats_goal) * 100, 100)
                                    : 0;
                            @endphp
                            <div class="progress-bar" style="width: {{ $fatsPercent }}%; background-color: #4bc0c0;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comidas del día -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Comidas del día</h5>
                    @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.createNutrition'))
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-plus-circle"></i> Agregar Comida
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('nutrition.create', ['date' => $date, 'meal_type' => 'breakfast']) }}">🌅 Desayuno</a></li>
                                <li><a class="dropdown-item" href="{{ route('nutrition.create', ['date' => $date, 'meal_type' => 'lunch']) }}">☀️ Almuerzo</a></li>
                                <li><a class="dropdown-item" href="{{ route('nutrition.create', ['date' => $date, 'meal_type' => 'dinner']) }}">🌙 Cena</a></li>
                                <li><a class="dropdown-item" href="{{ route('nutrition.create', ['date' => $date, 'meal_type' => 'snack']) }}">🍎 Merienda</a></li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if($meals->count() > 0)
                    @php
                        $mealsByType = $meals->groupBy('meal_type');
                        $mealTypeIcons = [
                            'breakfast' => '🌅',
                            'lunch' => '☀️',
                            'dinner' => '🌙',
                            'snack' => '🍎'
                        ];
                    @endphp

                    @foreach(['breakfast', 'lunch', 'snack', 'dinner'] as $type)
                        @if(isset($mealsByType[$type]))
                            <div class="meal-section mb-4">
                                <h6 class="text-primary mb-3">
                                    {{ $mealTypeIcons[$type] }} {{ ucfirst($mealsByType[$type]->first()->meal_type_name) }}
                                </h6>

                                @foreach($mealsByType[$type] as $meal)
                                    <div class="meal-card card mb-2">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                @if($meal->image_path)
                                                    <div class="col-md-2">
                                                        <img src="{{ asset('storage/' . $meal->image_path) }}"
                                                             class="img-fluid rounded"
                                                             alt="{{ $meal->food_name }}"
                                                             style="max-height: 80px; object-fit: cover;">
                                                    </div>
                                                @endif

                                                <div class="col-md-{{ $meal->image_path ? '7' : '9' }}">
                                                    <h6 class="mb-1">{{ $meal->food_name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $meal->quantity }} {{ $meal->unit }}
                                                        @if($meal->meal_time)
                                                            • {{ substr($meal->meal_time, 0, 5) }}
                                                        @endif
                                                    </small>
                                                    @if($meal->description)
                                                        <p class="mb-0 mt-1"><small>{{ $meal->description }}</small></p>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 text-end">
                                                    <div class="nutrition-badges">
                                                        <span class="badge bg-danger">{{ $meal->calories }} kcal</span>
                                                        <span class="badge bg-primary">P: {{ $meal->proteins }}g</span>
                                                        <span class="badge bg-warning">C: {{ $meal->carbs }}g</span>
                                                        <span class="badge bg-info">G: {{ $meal->fats }}g</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.updateNutrition'))
                                                            <a href="{{ route('nutrition.edit', $meal->id) }}" class="btn btn-warning btn-sm">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        @endif
                                                        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.deleteNutrition'))
                                                            <form action="{{ route('nutrition.delete', $meal->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay comidas registradas para este día</h4>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.createNutrition'))
                            <p class="text-muted">Comienza a registrar tus comidas</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

<style>
.card-icon {
    width: 60px;
    height: 60px;
    font-size: 28px;
}

.meal-card {
    border-left: 4px solid #0d6efd;
    transition: all 0.3s ease;
}

.meal-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    transform: translateX(5px);
}

.nutrition-badges .badge {
    margin: 2px;
    font-size: 0.75rem;
}
</style>

<script type="module">
function changeDate(days) {
    const dateInput = document.querySelector('input[name="date"]');
    const currentDate = new Date(dateInput.value);
    currentDate.setDate(currentDate.getDate() + days);
    dateInput.value = currentDate.toISOString().split('T')[0];
    document.getElementById('dateForm').submit();
}

window.changeDate = changeDate;

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

@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Metas Nutricionales</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('nutrition.index') }}">Nutrición</a></li>
                <li class="breadcrumb-item active">Metas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Configurar Metas Diarias</h3>
                    </div>

                    <div class="card-body mt-3">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('nutrition.goals.update') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Información:</strong> Establece tus metas nutricionales diarias. Estas te ayudarán a llevar un mejor control de tu alimentación.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-fire text-danger"></i> Calorías diarias *
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control"
                                           name="daily_calories_goal"
                                           value="{{ old('daily_calories_goal', $goal->daily_calories_goal) }}"
                                           min="800"
                                           max="5000"
                                           required>
                                    <span class="input-group-text">kcal</span>
                                </div>
                                <small class="text-muted">Rango recomendado: 1500-2500 kcal</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-egg text-primary"></i> Proteínas diarias *
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.1"
                                           class="form-control"
                                           name="daily_proteins_goal"
                                           value="{{ old('daily_proteins_goal', $goal->daily_proteins_goal) }}"
                                           min="0"
                                           max="500"
                                           required>
                                    <span class="input-group-text">g</span>
                                </div>
                                <small class="text-muted">Recomendado: 0.8-2g por kg de peso</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-bread-slice text-warning"></i> Carbohidratos diarios *
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.1"
                                           class="form-control"
                                           name="daily_carbs_goal"
                                           value="{{ old('daily_carbs_goal', $goal->daily_carbs_goal) }}"
                                           min="0"
                                           max="1000"
                                           required>
                                    <span class="input-group-text">g</span>
                                </div>
                                <small class="text-muted">45-65% de calorías totales</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-droplet-fill text-info"></i> Grasas diarias *
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.1"
                                           class="form-control"
                                           name="daily_fats_goal"
                                           value="{{ old('daily_fats_goal', $goal->daily_fats_goal) }}"
                                           min="0"
                                           max="500"
                                           required>
                                    <span class="input-group-text">g</span>
                                </div>
                                <small class="text-muted">20-35% de calorías totales</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-heart-pulse text-success"></i> Fibra diaria
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.1"
                                           class="form-control"
                                           name="daily_fiber_goal"
                                           value="{{ old('daily_fiber_goal', $goal->daily_fiber_goal) }}"
                                           min="0"
                                           max="100">
                                    <span class="input-group-text">g</span>
                                </div>
                                <small class="text-muted">Recomendado: 25-30g</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-droplet text-primary"></i> Agua diaria
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="1"
                                           class="form-control"
                                           name="daily_water_goal"
                                           value="{{ old('daily_water_goal', $goal->daily_water_goal) }}"
                                           min="0"
                                           max="10000">
                                    <span class="input-group-text">ml</span>
                                </div>
                                <small class="text-muted">Recomendado: 2000-3000ml</small>
                            </div>

                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">Vista previa de distribución calórica:</h6>
                                        <div class="row" id="caloriePreview">
                                            <div class="col-md-4">
                                                <strong>Proteínas:</strong>
                                                <div class="progress mt-1" style="height: 20px;">
                                                    <div class="progress-bar bg-primary" id="proteinBar"></div>
                                                </div>
                                                <small class="text-muted" id="proteinText"></small>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Carbohidratos:</strong>
                                                <div class="progress mt-1" style="height: 20px;">
                                                    <div class="progress-bar bg-warning" id="carbBar"></div>
                                                </div>
                                                <small class="text-muted" id="carbText"></small>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Grasas:</strong>
                                                <div class="progress mt-1" style="height: 20px;">
                                                    <div class="progress-bar bg-info" id="fatBar"></div>
                                                </div>
                                                <small class="text-muted" id="fatText"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Metas
                                </button>
                                <a href="{{ route('nutrition.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script type="module">
$(document).ready(function() {
    function updateCaloriePreview() {
        const proteins = parseFloat($('input[name="daily_proteins_goal"]').val()) || 0;
        const carbs = parseFloat($('input[name="daily_carbs_goal"]').val()) || 0;
        const fats = parseFloat($('input[name="daily_fats_goal"]').val()) || 0;

        const proteinCals = proteins * 4;
        const carbCals = carbs * 4;
        const fatCals = fats * 9;
        const totalCals = proteinCals + carbCals + fatCals;

        if (totalCals > 0) {
            const proteinPercent = (proteinCals / totalCals * 100).toFixed(1);
            const carbPercent = (carbCals / totalCals * 100).toFixed(1);
            const fatPercent = (fatCals / totalCals * 100).toFixed(1);

            $('#proteinBar').css('width', proteinPercent + '%').text(proteinPercent + '%');
            $('#carbBar').css('width', carbPercent + '%').text(carbPercent + '%');
            $('#fatBar').css('width', fatPercent + '%').text(fatPercent + '%');

            $('#proteinText').text(`${proteinCals.toFixed(0)} kcal (${proteins}g)`);
            $('#carbText').text(`${carbCals.toFixed(0)} kcal (${carbs}g)`);
            $('#fatText').text(`${fatCals.toFixed(0)} kcal (${fats}g)`);
        }
    }

    $('input[name="daily_proteins_goal"], input[name="daily_carbs_goal"], input[name="daily_fats_goal"]').on('input', updateCaloriePreview);

    updateCaloriePreview();
});
</script>

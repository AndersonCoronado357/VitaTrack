@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Metas de Sueño</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sleep.index') }}">Sueño y Descanso</a></li>
                <li class="breadcrumb-item active">Metas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Configurar Metas de Descanso</h3>
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

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Recomendaciones generales:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Adultos (18-64 años): 7-9 horas por noche</li>
                                <li>Adultos mayores (65+ años): 7-8 horas por noche</li>
                                <li>Mantener horarios consistentes ayuda a regular el ciclo circadiano</li>
                            </ul>
                        </div>

                        <form action="{{ route('sleep.goals.update') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-12">
                                <label class="form-label">
                                    <i class="bi bi-clock-history text-primary"></i> Meta de horas de sueño diarias *
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           step="0.5"
                                           class="form-control"
                                           name="target_hours"
                                           value="{{ old('target_hours', $goal->target_hours) }}"
                                           min="4"
                                           max="12"
                                           required>
                                    <span class="input-group-text">horas</span>
                                </div>
                                <small class="text-muted">Rango recomendado: 7-9 horas</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-moon text-primary"></i> Hora ideal para acostarse
                                </label>
                                <input type="time"
                                       class="form-control"
                                       name="target_bedtime"
                                       value="{{ old('target_bedtime', $goal->target_bedtime ? substr($goal->target_bedtime, 0, 5) : '') }}">
                                <small class="text-muted">Opcional: establece una rutina</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-sunrise text-warning"></i> Hora ideal para despertar
                                </label>
                                <input type="time"
                                       class="form-control"
                                       name="target_wake_time"
                                       value="{{ old('target_wake_time', $goal->target_wake_time ? substr($goal->target_wake_time, 0, 5) : '') }}">
                                <small class="text-muted">Opcional: mantén consistencia</small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">
                                    <i class="bi bi-exclamation-triangle text-warning"></i> Máximo de interrupciones aceptables *
                                </label>
                                <input type="number"
                                       class="form-control"
                                       name="max_interruptions"
                                       value="{{ old('max_interruptions', $goal->max_interruptions) }}"
                                       min="0"
                                       max="10"
                                       required>
                                <small class="text-muted">Recomendado: 0-2 interrupciones</small>
                            </div>

                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="bi bi-lightbulb text-warning"></i> Consejos para mejorar tu sueño:</h6>
                                        <ul class="mb-0">
                                            <li>Mantén una temperatura fresca en tu habitación (18-20°C)</li>
                                            <li>Evita pantallas al menos 1 hora antes de dormir</li>
                                            <li>Establece una rutina relajante antes de acostarte</li>
                                            <li>Evita cafeína después de las 2 PM</li>
                                            <li>Haz ejercicio regularmente, pero no cerca de la hora de dormir</li>
                                            <li>Mantén tu habitación oscura y silenciosa</li>
                                        </ul>
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
                                <a href="{{ route('sleep.index') }}" class="btn btn-secondary">
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
    // Calcular horas automáticamente si ambos horarios están establecidos
    $('input[name="target_bedtime"], input[name="target_wake_time"]').on('change', function() {
        const bedtime = $('input[name="target_bedtime"]').val();
        const wakeTime = $('input[name="target_wake_time"]').val();

        if (bedtime && wakeTime) {
            const bed = new Date('2000-01-01 ' + bedtime);
            let wake = new Date('2000-01-01 ' + wakeTime);

            if (wake < bed) {
                wake.setDate(wake.getDate() + 1);
            }

            const diffMs = wake - bed;
            const diffHrs = diffMs / (1000 * 60 * 60);

            if (Math.abs(diffHrs - parseFloat($('input[name="target_hours"]').val())) > 0.5) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sugerencia',
                    text: `El horario que estableciste da ${diffHrs.toFixed(1)} horas de sueño. ¿Quieres actualizar tu meta de horas?`,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'No, mantener'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('input[name="target_hours"]').val(diffHrs.toFixed(1));
                    }
                });
            }
        }
    });
});
</script>

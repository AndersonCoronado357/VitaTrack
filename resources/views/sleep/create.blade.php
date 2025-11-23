@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Registrar Sueño</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sleep.index') }}">Sueño y Descanso</a></li>
                <li class="breadcrumb-item active">Registrar</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Nuevo Registro de Sueño</h3>
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

                        <form action="{{ route('sleep.store') }}" method="POST" class="row g-3" id="sleepForm">
                            @csrf

                            <div class="col-md-12">
                                <label class="form-label">Fecha de inicio del sueño *</label>
                                <input type="date" class="form-control" name="sleep_date" value="{{ old('sleep_date', $date) }}" required>
                                <small class="text-muted">Fecha en que te acostaste</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hora de acostarse *</label>
                                <input type="time" class="form-control" name="bedtime" id="bedtime" value="{{ old('bedtime') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hora de despertar *</label>
                                <input type="time" class="form-control" name="wake_time" id="wake_time" value="{{ old('wake_time') }}" required>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-info" id="hoursCalculated" style="display: none;">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Horas de sueño calculadas:</strong> <span id="calculatedHours"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Interrupciones durante la noche *</label>
                                <input type="number" class="form-control" name="interruptions" value="{{ old('interruptions', 0) }}" min="0" max="20" required>
                                <small class="text-muted">Veces que te despertaste</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Calidad del sueño *</label>
                                <select class="form-select" name="quality" required>
                                    <option value="">Seleccione...</option>
                                    <option value="excellent" {{ old('quality') == 'excellent' ? 'selected' : '' }}>⭐ Excelente</option>
                                    <option value="good" {{ old('quality') == 'good' ? 'selected' : '' }}>😊 Buena</option>
                                    <option value="fair" {{ old('quality') == 'fair' ? 'selected' : '' }}>😐 Regular</option>
                                    <option value="poor" {{ old('quality') == 'poor' ? 'selected' : '' }}>☹️ Mala</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="felt_rested" value="1" id="feltRested" {{ old('felt_rested') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feltRested">
                                        Me sentí descansado/a al despertar
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notas adicionales</label>
                                <textarea class="form-control" name="notes" rows="3" maxlength="500" placeholder="Sueños, factores que afectaron el sueño, etc.">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Registro
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
    function calculateHours() {
        const bedtime = $('#bedtime').val();
        const wakeTime = $('#wake_time').val();

        if (bedtime && wakeTime) {
            const bed = new Date('2000-01-01 ' + bedtime);
            let wake = new Date('2000-01-01 ' + wakeTime);

            // Si wake_time es menor, añadir un día
            if (wake < bed) {
                wake.setDate(wake.getDate() + 1);
            }

            const diffMs = wake - bed;
            const diffHrs = diffMs / (1000 * 60 * 60);

            $('#calculatedHours').text(diffHrs.toFixed(1) + ' horas');
            $('#hoursCalculated').show();
        }
    }

    $('#bedtime, #wake_time').on('change', calculateHours);
});
</script>

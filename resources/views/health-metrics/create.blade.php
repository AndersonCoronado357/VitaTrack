@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Registrar Métrica de Salud</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('health-metrics.index') }}">Métricas de Salud</a></li>
                <li class="breadcrumb-item active">Registrar</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Nueva Medición</h3>
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

                        <form action="{{ route('health-metrics.store') }}" method="POST" class="row g-3" id="metricForm">
                            @csrf

                            <div class="col-md-12">
                                <label class="form-label">Tipo de métrica *</label>
                                <select class="form-select" name="metric_type" id="metricType" required>
                                    <option value="blood_pressure" {{ old('metric_type', $metricType) == 'blood_pressure' ? 'selected' : '' }}>
                                        Presión Arterial (mmHg)
                                    </option>
                                    <option value="glucose" {{ old('metric_type', $metricType) == 'glucose' ? 'selected' : '' }}>
                                        Glucosa (mg/dL)
                                    </option>
                                    <option value="weight" {{ old('metric_type', $metricType) == 'weight' ? 'selected' : '' }}>
                                        Peso (kg)
                                    </option>
                                    <option value="heart_rate" {{ old('metric_type', $metricType) == 'heart_rate' ? 'selected' : '' }}>
                                        Frecuencia Cardíaca (bpm)
                                    </option>
                                    <option value="temperature" {{ old('metric_type', $metricType) == 'temperature' ? 'selected' : '' }}>
                                        Temperatura (°C)
                                    </option>
                                    <option value="oxygen" {{ old('metric_type', $metricType) == 'oxygen' ? 'selected' : '' }}>
                                        Oxígeno en Sangre (%)
                                    </option>
                                    <option value="cholesterol" {{ old('metric_type', $metricType) == 'cholesterol' ? 'selected' : '' }}>
                                        Colesterol (mg/dL)
                                    </option>
                                </select>
                            </div>

                            <!-- Presión arterial (dos valores) -->
                            <div class="col-md-6" id="systolicField">
                                <label class="form-label">Presión Sistólica *</label>
                                <input type="number" step="0.01" class="form-control" name="value" value="{{ old('value') }}" required>
                                <small class="text-muted">Valor superior (ej: 120)</small>
                            </div>

                            <div class="col-md-6" id="diastolicField">
                                <label class="form-label">Presión Diastólica</label>
                                <input type="number" step="0.01" class="form-control" name="value_secondary" value="{{ old('value_secondary') }}">
                                <small class="text-muted">Valor inferior (ej: 80)</small>
                            </div>

                            <!-- Glucosa (opción de ayunas) -->
                            <div class="col-md-12" id="fastingField" style="display: none;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_fasting" value="1" id="isFasting" {{ old('is_fasting') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isFasting">
                                        Medición en ayunas
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de medición *</label>
                                <input type="date" class="form-control" name="measured_date" value="{{ old('measured_date', \Carbon\Carbon::today()->format('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hora de medición</label>
                                <input type="time" class="form-control" name="measured_time" value="{{ old('measured_time') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notas</label>
                                <textarea class="form-control" name="notes" rows="3" maxlength="500">{{ old('notes') }}</textarea>
                                <small class="text-muted">Opcional: condiciones, síntomas, medicación, etc.</small>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-info" id="rangeInfo">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Rangos normales:</strong>
                                    <span id="rangeText"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Registrar
                                </button>
                                <a href="{{ route('health-metrics.index') }}" class="btn btn-secondary">
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
    const ranges = {
        blood_pressure: 'Sistólica: 90-120 mmHg, Diastólica: 60-80 mmHg',
        glucose: 'En ayunas: 70-100 mg/dL, Post-prandial: hasta 140 mg/dL',
        weight: 'Según IMC y contexto individual',
        heart_rate: '60-100 bpm en reposo',
        temperature: '36.1-37.2 °C',
        oxygen: '95-100%',
        cholesterol: 'Total: menos de 200 mg/dL'
    };

    function updateFields() {
        const metricType = $('#metricType').val();

        // Ocultar todos los campos especiales
        $('#diastolicField, #fastingField').hide();

        // Actualizar labels
        if (metricType === 'blood_pressure') {
            $('#systolicField label').text('Presión Sistólica *');
            $('#diastolicField').show();
            $('input[name="value_secondary"]').attr('required', true);
        } else {
            $('#systolicField label').text('Valor *');
            $('input[name="value_secondary"]').removeAttr('required');

            if (metricType === 'glucose') {
                $('#fastingField').show();
            }
        }

        // Actualizar información de rangos
        $('#rangeText').text(ranges[metricType] || 'Consulte con su médico');
    }

    $('#metricType').on('change', updateFields);
    updateFields();
});
</script>

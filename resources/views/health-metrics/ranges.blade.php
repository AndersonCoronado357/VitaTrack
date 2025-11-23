@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Configurar Rangos Personalizados</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('health-metrics.index') }}">Métricas de Salud</a></li>
                <li class="breadcrumb-item active">Rangos</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Importante:</strong> Estos rangos son personalizables según las recomendaciones de tu médico.
                    Los valores por defecto son orientativos para adultos sanos. Consulta con un profesional de la salud antes de modificarlos.
                </div>

                @foreach($metricTypes as $type)
                    @php
                        $range = $ranges->get($type);
                        $defaults = \App\Models\HealthMetric::getDefaultRanges($type);
                        $metricNames = [
                            'blood_pressure' => 'Presión Arterial',
                            'glucose' => 'Glucosa',
                            'weight' => 'Peso',
                            'heart_rate' => 'Frecuencia Cardíaca',
                            'temperature' => 'Temperatura',
                            'oxygen' => 'Oxígeno en Sangre',
                            'cholesterol' => 'Colesterol'
                        ];
                    @endphp

                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $metricNames[$type] }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('health-metrics.ranges.update') }}" method="POST" class="row g-3">
                                @csrf
                                <input type="hidden" name="metric_type" value="{{ $type }}">

                                <div class="col-md-12">
                                    <h6 class="text-success">Rango Normal</h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mínimo normal</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="min_normal"
                                           value="{{ $range->min_normal ?? $defaults['min_normal'] }}"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Máximo normal</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="max_normal"
                                           value="{{ $range->max_normal ?? $defaults['max_normal'] }}"
                                           required>
                                </div>

                                <div class="col-md-12">
                                    <h6 class="text-warning">Rango de Alerta</h6>
                                    <small class="text-muted">Valores fuera de este rango generarán alertas</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mínimo antes de alerta</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="min_warning"
                                           value="{{ $range->min_warning ?? $defaults['min_warning'] }}"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Máximo antes de alerta</label>
                                    <input type="number"
                                           step="0.01"
                                           class="form-control"
                                           name="max_warning"
                                           value="{{ $range->max_warning ?? $defaults['max_warning'] }}"
                                           required>
                                </div>

                                @if($type === 'blood_pressure')
                                    <div class="col-md-12">
                                        <h6 class="text-info">Presión Diastólica</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Mínimo normal (diastólica)</label>
                                        <input type="number"
                                               step="0.01"
                                               class="form-control"
                                               name="min_normal_secondary"
                                               value="{{ $range->min_normal_secondary ?? $defaults['min_normal_secondary'] }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Máximo normal (diastólica)</label>
                                        <input type="number"
                                               step="0.01"
                                               class="form-control"
                                               name="max_normal_secondary"
                                               value="{{ $range->max_normal_secondary ?? $defaults['max_normal_secondary'] }}">
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-save"></i> Guardar Rangos
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="resetDefaults('{{ $type }}')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar Valores por Defecto
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-3">
                    <a href="{{ route('health-metrics.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

<script>
function resetDefaults(metricType) {
    const defaults = {
        blood_pressure: { min_normal: 90, max_normal: 120, min_warning: 70, max_warning: 140, min_normal_secondary: 60, max_normal_secondary: 80 },
        glucose: { min_normal: 70, max_normal: 100, min_warning: 50, max_warning: 140 },
        weight: { min_normal: 50, max_normal: 100, min_warning: 40, max_warning: 150 },
        heart_rate: { min_normal: 60, max_normal: 100, min_warning: 40, max_warning: 120 },
        temperature: { min_normal: 36.1, max_normal: 37.2, min_warning: 35.0, max_warning: 38.5 },
        oxygen: { min_normal: 95, max_normal: 100, min_warning: 90, max_warning: 100 },
        cholesterol: { min_normal: 0, max_normal: 200, min_warning: 0, max_warning: 240 }
    };

    const values = defaults[metricType];
    const form = event.target.closest('form');

    form.querySelector('input[name="min_normal"]').value = values.min_normal;
    form.querySelector('input[name="max_normal"]').value = values.max_normal;
    form.querySelector('input[name="min_warning"]').value = values.min_warning;
    form.querySelector('input[name="max_warning"]').value = values.max_warning;

    if (values.min_normal_secondary) {
        form.querySelector('input[name="min_normal_secondary"]').value = values.min_normal_secondary;
        form.querySelector('input[name="max_normal_secondary"]').value = values.max_normal_secondary;
    }
}
</script>

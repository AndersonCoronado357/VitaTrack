@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Editar Métrica de Salud</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('health-metrics.index') }}">Métricas de Salud</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Editar Medición - {{ $metric->metric_type_name }}</h3>
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

                        <form action="{{ route('health-metrics.update') }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="metric_id" value="{{ $metric->id }}">

                            <div class="col-md-12">
                                <label class="form-label">Tipo de métrica</label>
                                <input type="text" class="form-control" value="{{ $metric->metric_type_name }}" disabled>
                            </div>

                            @if($metric->metric_type === 'blood_pressure')
                                <div class="col-md-6">
                                    <label class="form-label">Presión Sistólica *</label>
                                    <input type="number" step="0.01" class="form-control" name="value" value="{{ old('value', $metric->value) }}" required>
                                    <small class="text-muted">Valor superior</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Presión Diastólica *</label>
                                    <input type="number" step="0.01" class="form-control" name="value_secondary" value="{{ old('value_secondary', $metric->value_secondary) }}" required>
                                    <small class="text-muted">Valor inferior</small>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <label class="form-label">Valor *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" name="value" value="{{ old('value', $metric->value) }}" required>
                                        <span class="input-group-text">{{ $metric->unit }}</span>
                                    </div>
                                </div>
                            @endif

                            @if($metric->metric_type === 'glucose')
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_fasting" value="1" id="isFasting" {{ old('is_fasting', $metric->is_fasting) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isFasting">
                                            Medición en ayunas
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">Fecha de medición *</label>
                                <input type="date" class="form-control" name="measured_date" value="{{ old('measured_date', $metric->measured_date->format('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hora de medición</label>
                                <input type="time" class="form-control" name="measured_time" value="{{ old('measured_time', $metric->measured_time ? substr($metric->measured_time, 0, 5) : '') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notas</label>
                                <textarea class="form-control" name="notes" rows="3" maxlength="500">{{ old('notes', $metric->notes) }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-{{ $metric->status_color }}">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Estado actual:</strong> {{ $metric->status_text }}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Actualizar
                                </button>
                                <a href="{{ route('health-metrics.index', ['metric_type' => $metric->metric_type]) }}" class="btn btn-secondary">
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

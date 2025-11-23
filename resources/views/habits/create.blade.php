@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Nuevo Hábito</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('habits.index') }}">Hábitos</a></li>
                <li class="breadcrumb-item active">Nuevo Hábito</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <h3>Crear Nuevo Hábito</h3>
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

                <form action="{{ route('habits.store') }}" class="row g-3" method="POST">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Nombre del hábito *</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Ej: Hacer ejercicio"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Frecuencia *</label>
                        <select class="form-select @error('frequency') is-invalid @enderror"
                                name="frequency"
                                required>
                            <option value="">Seleccione...</option>
                            <option value="daily" {{ old('frequency') === 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="weekly" {{ old('frequency') === 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ old('frequency') === 'monthly' ? 'selected' : '' }}>Mensual</option>
                        </select>
                        @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control"
                                  name="description"
                                  rows="3"
                                  placeholder="Describe brevemente tu hábito...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Meta (veces por período) *</label>
                        <input type="number"
                               class="form-control @error('goal_count') is-invalid @enderror"
                               name="goal_count"
                               value="{{ old('goal_count', 1) }}"
                               min="1"
                               required>
                        <small class="text-muted">¿Cuántas veces quieres completar este hábito?</small>
                        @error('goal_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hora de recordatorio</label>
                        <input type="time"
                               class="form-control"
                               name="reminder_time"
                               value="{{ old('reminder_time') }}">
                        <small class="text-muted">Opcional: hora para recordarte</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Color del hábito</label>
                        <input type="color"
                               class="form-control form-control-color w-100"
                               name="color"
                               value="{{ old('color', '#0d6efd') }}"
                               style="height: 45px;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de inicio *</label>
                        <input type="date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               name="start_date"
                               value="{{ old('start_date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de fin</label>
                        <input type="date"
                               class="form-control"
                               name="end_date"
                               value="{{ old('end_date') }}">
                        <small class="text-muted">Opcional: dejar vacío para hábito continuo</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Icono</label>
                        <select class="form-select" name="icon" id="iconSelect">
                            <option value="bi-check-circle" selected>✓ Check</option>
                            <option value="bi-heart-fill">♥ Corazón</option>
                            <option value="bi-bicycle">🚴 Bicicleta</option>
                            <option value="bi-book">📖 Libro</option>
                            <option value="bi-droplet-fill">💧 Agua</option>
                            <option value="bi-moon-stars">🌙 Dormir</option>
                            <option value="bi-cup-hot">☕ Café</option>
                            <option value="bi-trophy">🏆 Meta</option>
                            <option value="bi-lightning-fill">⚡ Energía</option>
                            <option value="bi-flower1">🌸 Meditación</option>
                            <option value="bi-sun">☀️ Sol</option>
                            <option value="bi-music-note">🎵 Música</option>
                            <option value="bi-camera">📷 Foto</option>
                            <option value="bi-brush">🎨 Arte</option>
                        </select>
                        <div class="mt-2 text-center">
                            <i id="iconPreview" class="bi-check-circle" style="font-size: 2rem; color: #0d6efd;"></i>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Hábito
                        </button>
                        <a href="{{ route('habits.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </section>
@endsection

<script type="module">
$(document).ready(function() {
    // Preview del icono
    $('#iconSelect, input[name="color"]').on('change', function() {
        const selectedIcon = $('#iconSelect').val();
        const selectedColor = $('input[name="color"]').val();

        $('#iconPreview').removeClass().addClass(selectedIcon);
        $('#iconPreview').css('color', selectedColor);
    });
});
</script>

<style>
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

#iconPreview {
    transition: all 0.3s ease;
}
</style>

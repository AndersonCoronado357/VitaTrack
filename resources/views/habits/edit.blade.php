@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Editar Hábito</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('habits.index') }}">Hábitos</a></li>
                <li class="breadcrumb-item active">Editar Hábito</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <h3>Editar Hábito</h3>
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

                <form action="{{ route('habits.update') }}" class="row g-3" method="POST" id="formEdit">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="habit_id" value="{{ $habit->id }}" />

                    <div class="col-md-6">
                        <label class="form-label">Nombre del hábito *</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name', $habit->name) }}"
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
                            <option value="daily" {{ old('frequency', $habit->frequency) === 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="weekly" {{ old('frequency', $habit->frequency) === 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ old('frequency', $habit->frequency) === 'monthly' ? 'selected' : '' }}>Mensual</option>
                        </select>
                        @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control"
                                  name="description"
                                  rows="3">{{ old('description', $habit->description) }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Meta (veces por período) *</label>
                        <input type="number"
                               class="form-control @error('goal_count') is-invalid @enderror"
                               name="goal_count"
                               value="{{ old('goal_count', $habit->goal_count) }}"
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
                               value="{{ old('reminder_time', $habit->reminder_time ? substr($habit->reminder_time, 0, 5) : '') }}">
                        <small class="text-muted">Opcional: hora para recordarte</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Color del hábito</label>
                        <input type="color"
                               class="form-control form-control-color w-100"
                               name="color"
                               value="{{ old('color', $habit->color) }}"
                               style="height: 45px;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de inicio *</label>
                        <input type="date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               name="start_date"
                               value="{{ old('start_date', $habit->start_date->format('Y-m-d')) }}"
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
                               value="{{ old('end_date', $habit->end_date ? $habit->end_date->format('Y-m-d') : '') }}">
                        <small class="text-muted">Opcional: dejar vacío para hábito continuo</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Icono</label>
                        <select class="form-select" name="icon" id="iconSelect">
                            <option value="bi-check-circle" {{ old('icon', $habit->icon) === 'bi-check-circle' ? 'selected' : '' }}>✓ Check</option>
                            <option value="bi-heart-fill" {{ old('icon', $habit->icon) === 'bi-heart-fill' ? 'selected' : '' }}>♥ Corazón</option>
                            <option value="bi-bicycle" {{ old('icon', $habit->icon) === 'bi-bicycle' ? 'selected' : '' }}>🚴 Bicicleta</option>
                            <option value="bi-book" {{ old('icon', $habit->icon) === 'bi-book' ? 'selected' : '' }}>📖 Libro</option>
                            <option value="bi-droplet-fill" {{ old('icon', $habit->icon) === 'bi-droplet-fill' ? 'selected' : '' }}>💧 Agua</option>
                            <option value="bi-moon-stars" {{ old('icon', $habit->icon) === 'bi-moon-stars' ? 'selected' : '' }}>🌙 Dormir</option>
                            <option value="bi-cup-hot" {{ old('icon', $habit->icon) === 'bi-cup-hot' ? 'selected' : '' }}>☕ Café</option>
                            <option value="bi-trophy" {{ old('icon', $habit->icon) === 'bi-trophy' ? 'selected' : '' }}>🏆 Meta</option>
                            <option value="bi-lightning-fill" {{ old('icon', $habit->icon) === 'bi-lightning-fill' ? 'selected' : '' }}>⚡ Energía</option>
                            <option value="bi-flower1" {{ old('icon', $habit->icon) === 'bi-flower1' ? 'selected' : '' }}>🌸 Meditación</option>
                            <option value="bi-sun" {{ old('icon', $habit->icon) === 'bi-sun' ? 'selected' : '' }}>☀️ Sol</option>
                            <option value="bi-music-note" {{ old('icon', $habit->icon) === 'bi-music-note' ? 'selected' : '' }}>🎵 Música</option>
                            <option value="bi-camera" {{ old('icon', $habit->icon) === 'bi-camera' ? 'selected' : '' }}>📷 Foto</option>
                            <option value="bi-brush" {{ old('icon', $habit->icon) === 'bi-brush' ? 'selected' : '' }}>🎨 Arte</option>
                        </select>
                        <div class="mt-2 text-center">
                            <i id="iconPreview" class="{{ old('icon', $habit->icon) }}" style="font-size: 2rem; color: {{ old('color', $habit->color) }};"></i>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="active"
                                   value="1"
                                   id="activeCheck"
                                   {{ old('active', $habit->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activeCheck">
                                <strong>Hábito activo</strong>
                                <br>
                                <small class="text-muted">Desactiva el hábito si quieres pausarlo temporalmente</small>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar Hábito
                        </button>
                        <a href="{{ route('habits.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <a href="{{ route('habits.statistics', $habit->id) }}" class="btn btn-info">
                            <i class="bi bi-graph-up"></i> Ver Estadísticas
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

    // Confirmación antes de enviar
    $('#formEdit').on('submit', function(e) {
        const isActive = $('#activeCheck').is(':checked');

        if (!isActive) {
            e.preventDefault();

            Swal.fire({
                title: '¿Desactivar hábito?',
                text: 'Estás a punto de desactivar este hábito. Podrás reactivarlo más tarde.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar cambios',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formEdit')[0].submit();
                }
            });
        }
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

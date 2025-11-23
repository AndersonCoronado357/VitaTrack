@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Nueva Cita</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Citas</a></li>
                <li class="breadcrumb-item active">Nueva Cita</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Programar Nueva Cita</h3>
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

                        <form action="{{ route('appointments.store') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-8">
                                <label class="form-label">Título de la cita *</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo de cita *</label>
                                <select class="form-select" name="type" id="appointmentType" required>
                                    <option value="medical" {{ old('type', $type) == 'medical' ? 'selected' : '' }}>🏥 Médica</option>
                                    <option value="personal" {{ old('type', $type) == 'personal' ? 'selected' : '' }}>👤 Personal</option>
                                    <option value="work" {{ old('type', $type) == 'work' ? 'selected' : '' }}>💼 Trabajo</option>
                                    <option value="other" {{ old('type', $type) == 'other' ? 'selected' : '' }}>📅 Otra</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha *</label>
                                <input type="date" class="form-control" name="appointment_date" value="{{ old('appointment_date', $date) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Hora *</label>
                                <input type="time" class="form-control" name="appointment_time" value="{{ old('appointment_time') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Duración (minutos) *</label>
                                <input type="number" class="form-control" name="duration" value="{{ old('duration', 30) }}" min="5" max="480" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Ubicación</label>
                                <input type="text" class="form-control" name="location" value="{{ old('location') }}" placeholder="Dirección, consultorio, sala, etc.">
                            </div>

                            <!-- Campos específicos para citas médicas -->
                            <div id="medicalFields" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre del médico</label>
                                        <input type="text" class="form-control" name="doctor_name" value="{{ old('doctor_name') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Especialidad</label>
                                        <input type="text" class="form-control" name="specialty" value="{{ old('specialty') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <h6>Recordatorio</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="reminder_enabled" value="1" id="reminderEnabled" {{ old('reminder_enabled', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="reminderEnabled">
                                        Activar recordatorio
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6" id="reminderMinutesField">
                                <label class="form-label">Recordar con anticipación</label>
                                <select class="form-select" name="reminder_minutes">
                                    <option value="15" {{ old('reminder_minutes') == 15 ? 'selected' : '' }}>15 minutos antes</option>
                                    <option value="30" {{ old('reminder_minutes') == 30 ? 'selected' : '' }}>30 minutos antes</option>
                                    <option value="60" {{ old('reminder_minutes', 60) == 60 ? 'selected' : '' }}>1 hora antes</option>
                                    <option value="120" {{ old('reminder_minutes') == 120 ? 'selected' : '' }}>2 horas antes</option>
                                    <option value="1440" {{ old('reminder_minutes') == 1440 ? 'selected' : '' }}>1 día antes</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Color en el calendario</label>
                                <input type="color" class="form-control form-control-color w-100" name="color" value="{{ old('color', '#0d6efd') }}" style="height: 45px;">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notas adicionales</label>
                                <textarea class="form-control" name="notes" rows="3" maxlength="1000">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Guardar Cita
                                </button>
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
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
    function toggleMedicalFields() {
        const type = $('#appointmentType').val();
        if (type === 'medical') {
            $('#medicalFields').show();
        } else {
            $('#medicalFields').hide();
        }
    }

    function toggleReminderField() {
        if ($('#reminderEnabled').is(':checked')) {
            $('#reminderMinutesField').show();
        } else {
            $('#reminderMinutesField').hide();
        }
    }

    $('#appointmentType').on('change', toggleMedicalFields);
    $('#reminderEnabled').on('change', toggleReminderField);

    toggleMedicalFields();
    toggleReminderField();
});
</script>

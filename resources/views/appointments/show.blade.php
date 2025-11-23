@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Detalles de la Cita</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Citas</a></li>
                <li class="breadcrumb-item active">Detalles</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header" style="background-color: {{ $appointment->color }}20; border-left: 5px solid {{ $appointment->color }};">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="{{ $appointment->type_icon }} me-2"></i>
                                {{ $appointment->title }}
                            </h3>
                            <span class="badge bg-{{ $appointment->status_color }}" style="font-size: 1rem;">
                                {{ $appointment->status_text }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body mt-3">
                        <div class="row g-4">
                            <!-- Información principal -->
                            <div class="col-md-12">
                                <h5><i class="bi bi-info-circle text-primary"></i> Información Principal</h5>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    <strong>Fecha:</strong>
                                    <p class="ms-4 mb-0">{{ $appointment->appointment_date->format('l, d \d\e F \d\e Y') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    <strong>Horario:</strong>
                                    <p class="ms-4 mb-0">{{ substr($appointment->appointment_time, 0, 5) }} - {{ $appointment->end_time }} ({{ $appointment->duration }} min)</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-tag text-primary me-2"></i>
                                    <strong>Tipo:</strong>
                                    <p class="ms-4 mb-0">{{ $appointment->type_name }}</p>
                                </div>
                            </div>

                            @if($appointment->location)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                        <strong>Ubicación:</strong>
                                        <p class="ms-4 mb-0">{{ $appointment->location }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($appointment->description)
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <i class="bi bi-card-text text-primary me-2"></i>
                                        <strong>Descripción:</strong>
                                        <p class="ms-4 mb-0">{{ $appointment->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Información médica -->
                            @if($appointment->type === 'medical' && ($appointment->doctor_name || $appointment->specialty))
                                <div class="col-md-12">
                                    <h5><i class="bi bi-heart-pulse text-danger"></i> Información Médica</h5>
                                    <hr>
                                </div>

                                @if($appointment->doctor_name)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="bi bi-person-badge text-danger me-2"></i>
                                            <strong>Médico:</strong>
                                            <p class="ms-4 mb-0">Dr. {{ $appointment->doctor_name }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($appointment->specialty)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="bi bi-hospital text-danger me-2"></i>
                                            <strong>Especialidad:</strong>
                                            <p class="ms-4 mb-0">{{ $appointment->specialty }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- Recordatorio -->
                            <div class="col-md-12">
                                <h5><i class="bi bi-bell text-warning"></i> Recordatorio</h5>
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-bell-fill text-warning me-2"></i>
                                    <strong>Estado:</strong>
                                    <p class="ms-4 mb-0">
                                        @if($appointment->reminder_enabled)
                                            <span class="badge bg-success">Activado</span>
                                        @else
                                            <span class="badge bg-secondary">Desactivado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($appointment->reminder_enabled)
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="bi bi-clock-history text-warning me-2"></i>
                                        <strong>Anticipación:</strong>
                                        <p class="ms-4 mb-0">
                                            @if($appointment->reminder_minutes < 60)
                                                {{ $appointment->reminder_minutes }} minutos antes
                                            @elseif($appointment->reminder_minutes < 1440)
                                                {{ $appointment->reminder_minutes / 60 }} hora(s) antes
                                            @else
                                                {{ $appointment->reminder_minutes / 1440 }} día(s) antes
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Notas -->
                            @if($appointment->notes)
                                <div class="col-md-12">
                                    <h5><i class="bi bi-journal-text text-info"></i> Notas</h5>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        {{ $appointment->notes }}
                                    </div>
                                </div>
                            @endif

                            <!-- Cambio rápido de estado -->
                            @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.updateAppointments') && $appointment->status !== 'cancelled')
                                <div class="col-md-12">
                                    <h5><i class="bi bi-pencil-square text-success"></i> Acciones Rápidas</h5>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    <div class="btn-group" role="group">
                                        @if($appointment->status !== 'completed')
                                            <button type="button" class="btn btn-success" onclick="updateStatus('{{ $appointment->id }}', 'completed')">
                                                <i class="bi bi-check-circle"></i> Marcar como Completada
                                            </button>
                                        @endif
                                        @if($appointment->status === 'scheduled')
                                            <button type="button" class="btn btn-warning" onclick="updateStatus('{{ $appointment->id }}', 'rescheduled')">
                                                <i class="bi bi-arrow-repeat"></i> Reprogramar
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-danger" onclick="updateStatus('{{ $appointment->id }}', 'cancelled')">
                                            <i class="bi bi-x-circle"></i> Cancelar
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Botones de acción -->
                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-12 text-center">
                                @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.updateAppointments'))
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> Editar Cita
                                    </a>
                                @endif
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Volver a Lista
                                </a>
                                @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.calendarAppointments'))
                                    <a href="{{ route('appointments.calendar', ['year' => $appointment->appointment_date->year, 'month' => $appointment->appointment_date->month]) }}" class="btn btn-info">
                                        <i class="bi bi-calendar3"></i> Ver en Calendario
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script type="module">
function updateStatus(appointmentId, status) {
    const statusTexts = {
        'completed': '¿Marcar esta cita como completada?',
        'cancelled': '¿Cancelar esta cita?',
        'rescheduled': '¿Marcar esta cita para reprogramar?'
    };

    Swal.fire({
        title: statusTexts[status],
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("appointments.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    appointment_id: appointmentId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Estado actualizado',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo actualizar el estado'
                    });
                }
            });
        }
    });
}

window.updateStatus = updateStatus;
</script>

<style>
.info-item {
    margin-bottom: 1rem;
}

.info-item i {
    font-size: 1.2rem;
}
</style>

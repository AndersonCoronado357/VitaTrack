@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Citas y Calendario</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Citas y Calendario</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <!-- Acciones principales -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-3">
                    <div class="col-md-6">
                        <form action="{{ route('appointments.index') }}" method="GET" class="row g-2">
                            <div class="col-auto">
                                <label class="form-label">Estado:</label>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Todas</option>
                                    <option value="scheduled" {{ $status == 'scheduled' ? 'selected' : '' }}>Programadas</option>
                                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completadas</option>
                                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                                    <option value="rescheduled" {{ $status == 'rescheduled' ? 'selected' : '' }}>Reprogramadas</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end mt-2 mt-md-0">
                        @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.calendarAppointments'))
                            <a href="{{ route('appointments.calendar') }}" class="btn btn-info btn-sm">
                                <i class="bi bi-calendar3"></i> Ver Calendario
                            </a>
                        @endif
                        @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.createAppointments'))
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-plus-circle"></i> Nueva Cita
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('appointments.create', ['type' => 'medical']) }}">
                                        <i class="bi bi-heart-pulse text-danger"></i> Cita Médica
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('appointments.create', ['type' => 'personal']) }}">
                                        <i class="bi bi-person text-primary"></i> Cita Personal
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('appointments.create', ['type' => 'work']) }}">
                                        <i class="bi bi-briefcase text-info"></i> Cita de Trabajo
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('appointments.create', ['type' => 'other']) }}">
                                        <i class="bi bi-calendar-event text-secondary"></i> Otra
                                    </a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Citas de hoy -->
        @if($today->count() > 0)
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Citas de Hoy</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($today as $appointment)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100" style="border-left: 4px solid {{ $appointment->color }};">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">
                                                <i class="{{ $appointment->type_icon }} me-1"></i>
                                                {{ $appointment->title }}
                                            </h6>
                                            <span class="badge bg-{{ $appointment->status_color }}">
                                                {{ $appointment->status_text }}
                                            </span>
                                        </div>

                                        <div class="appointment-details">
                                            <div><i class="bi bi-clock me-2"></i>{{ substr($appointment->appointment_time, 0, 5) }} - {{ $appointment->end_time }}</div>
                                            @if($appointment->location)
                                                <div><i class="bi bi-geo-alt me-2"></i>{{ $appointment->location }}</div>
                                            @endif
                                            @if($appointment->doctor_name)
                                                <div><i class="bi bi-person-badge me-2"></i>{{ $appointment->doctor_name }}</div>
                                            @endif
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-primary flex-fill">
                                                <i class="bi bi-eye"></i> Ver
                                            </a>
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.updateAppointments'))
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning flex-fill">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Próximas citas (7 días) -->
        @if($upcoming->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Próximas Citas (7 días)</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($upcoming as $appointment)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <div class="date-badge" style="background-color: {{ $appointment->color }}20; border-left: 3px solid {{ $appointment->color }}; padding: 10px;">
                                            <strong style="font-size: 1.5rem; color: {{ $appointment->color }};">{{ $appointment->appointment_date->format('d') }}</strong>
                                            <br><small>{{ $appointment->appointment_date->format('M') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="mb-1">
                                            <i class="{{ $appointment->type_icon }} me-1"></i>
                                            {{ $appointment->title }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ substr($appointment->appointment_time, 0, 5) }}
                                            @if($appointment->location)
                                                | <i class="bi bi-geo-alt me-1"></i>{{ $appointment->location }}
                                            @endif
                                            @if($appointment->doctor_name)
                                                | <i class="bi bi-person-badge me-1"></i>{{ $appointment->doctor_name }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="badge bg-{{ $appointment->status_color }} me-2">{{ $appointment->status_text }}</span>
                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Todas las citas -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Todas las Citas</h5>

                @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>
                                            <strong>{{ $appointment->appointment_date->format('d/m/Y') }}</strong>
                                            <br><small class="text-muted">{{ $appointment->appointment_date->format('l') }}</small>
                                        </td>
                                        <td>{{ substr($appointment->appointment_time, 0, 5) }}</td>
                                        <td>
                                            <strong>{{ $appointment->title }}</strong>
                                            @if($appointment->doctor_name)
                                                <br><small class="text-muted">Dr. {{ $appointment->doctor_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="{{ $appointment->type_icon }}"></i>
                                            {{ $appointment->type_name }}
                                        </td>
                                        <td>{{ $appointment->location ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status_color }}">
                                                {{ $appointment->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.updateAppointments'))
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.deleteAppointments'))
                                                <form action="{{ route('appointments.delete', $appointment->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm btnDelete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $appointments->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay citas registradas</h4>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.createAppointments'))
                            <p class="text-muted">Comienza a programar tus citas</p>
                            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Nueva Cita
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

<script type="module">
$(document).ready(function() {
    $('.btnDelete').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: '¿Eliminar cita?',
            text: 'Esta acción no se puede revertir',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('form').submit();
            }
        });
    });
});
</script>

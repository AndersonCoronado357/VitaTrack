@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Calendario de Citas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Citas</a></li>
                <li class="breadcrumb-item active">Calendario</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!-- Navegación del calendario -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center mt-2">
                    <div class="col-md-4">
                        <div class="btn-group">
                            <a href="{{ route('appointments.calendar', ['year' => $currentDate->copy()->subMonth()->year, 'month' => $currentDate->copy()->subMonth()->month]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                            <button class="btn btn-primary" style="min-width: 200px;">
                                {{ $currentDate->format('F Y') }}
                            </button>
                            <a href="{{ route('appointments.calendar', ['year' => $currentDate->copy()->addMonth()->year, 'month' => $currentDate->copy()->addMonth()->month]) }}" class="btn btn-outline-primary">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="{{ route('appointments.calendar') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-calendar-today"></i> Hoy
                        </a>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('appointments.index') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-list"></i> Vista Lista
                        </a>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.createAppointments'))
                            <a href="{{ route('appointments.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Nueva Cita
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="card">
            <div class="card-body">
                <div class="calendar-container">
                    <table class="table table-bordered calendar-table">
                        <thead>
                            <tr>
                                <th class="text-center">Domingo</th>
                                <th class="text-center">Lunes</th>
                                <th class="text-center">Martes</th>
                                <th class="text-center">Miércoles</th>
                                <th class="text-center">Jueves</th>
                                <th class="text-center">Viernes</th>
                                <th class="text-center">Sábado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $firstDay = $currentDate->copy()->startOfMonth();
                                $lastDay = $currentDate->copy()->endOfMonth();
                                $startDay = $firstDay->copy()->startOfWeek();
                                $endDay = $lastDay->copy()->endOfWeek();
                                $today = \Carbon\Carbon::today();
                            @endphp

                            @while($startDay <= $endDay)
                                <tr>
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $currentDay = $startDay->copy()->addDays($i);
                                            $dateKey = $currentDay->format('Y-m-d');
                                            $dayAppointments = $appointmentsByDate->get($dateKey, collect());
                                            $isCurrentMonth = $currentDay->month === $currentDate->month;
                                            $isToday = $currentDay->isSameDay($today);
                                        @endphp
                                        <td class="calendar-day {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}" style="height: 120px; vertical-align: top;">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="day-number {{ $isToday ? 'badge bg-primary' : '' }}">
                                                    {{ $currentDay->day }}
                                                </span>
                                                @if($isCurrentMonth && \App\Helpers\RoleHelper::isAuthorized('Citas y Calendario.createAppointments'))
                                                    <a href="{{ route('appointments.create', ['date' => $dateKey]) }}" class="btn btn-sm btn-light" title="Agregar cita">
                                                        <i class="bi bi-plus"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="appointments-list">
                                                @foreach($dayAppointments->take(3) as $appointment)
                                                    <a href="{{ route('appointments.show', $appointment->id) }}"
                                                       class="appointment-badge"
                                                       style="background-color: {{ $appointment->color }}; color: white;"
                                                       title="{{ $appointment->title }} - {{ substr($appointment->appointment_time, 0, 5) }}">
                                                        <small>
                                                            <i class="{{ $appointment->type_icon }}"></i>
                                                            {{ substr($appointment->appointment_time, 0, 5) }} - {{ Str::limit($appointment->title, 20) }}
                                                        </small>
                                                    </a>
                                                @endforeach

                                                @if($dayAppointments->count() > 3)
                                                    <small class="text-muted">+{{ $dayAppointments->count() - 3 }} más</small>
                                                @endif
                                            </div>
                                        </td>
                                    @endfor
                                </tr>
                                @php
                                    $startDay->addWeek();
                                @endphp
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen del mes -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Resumen del Mes</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">{{ $appointments->count() }}</h3>
                            <p class="text-muted">Total de citas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">{{ $appointments->where('status', 'scheduled')->count() }}</h3>
                            <p class="text-muted">Programadas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-info">{{ $appointments->where('status', 'completed')->count() }}</h3>
                            <p class="text-muted">Completadas</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-danger">{{ $appointments->where('type', 'medical')->count() }}</h3>
                            <p class="text-muted">Médicas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
.calendar-table {
    margin-bottom: 0;
}

.calendar-table th {
    background-color: #f8f9fa;
    padding: 10px;
    font-weight: 600;
}

.calendar-day {
    padding: 8px;
    position: relative;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.calendar-day.today {
    background-color: #e7f3ff;
    border: 2px solid #0d6efd;
}

.day-number {
    font-weight: 600;
    font-size: 1.1rem;
}

.appointments-list {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.appointment-badge {
    display: block;
    padding: 3px 6px;
    border-radius: 3px;
    text-decoration: none;
    transition: opacity 0.2s;
}

.appointment-badge:hover {
    opacity: 0.8;
}

.calendar-container {
    overflow-x: auto;
}

@media (max-width: 768px) {
    .calendar-table {
        font-size: 0.8rem;
    }

    .appointment-badge small {
        font-size: 0.7rem;
    }
}
</style>

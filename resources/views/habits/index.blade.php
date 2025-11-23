@extends('layouts.app')

@section('content')

    <div class="pagetitle">
        <h1>Hábitos Diarios</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Hábitos</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Mis Hábitos</h3>
                        <small class="text-muted">Hoy: {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</small>
                    </div>

                    @if (\App\Helpers\RoleHelper::isAuthorized('Hábitos.createHabits'))
                        <div class="col-auto">
                            <a href="{{ route('habits.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill me-1"></i>Nuevo Hábito
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('habits.index') }}" class="navbar-search" method="GET">
                    <div class="row mt-3 mb-4">
                        <div class="col-md-2">
                            <select name="records_per_page" class="form-select" value="{{ $data->records_per_page }}">
                                <option value="6" {{ $data->records_per_page == 6 ? 'selected' : '' }}>6</option>
                                <option value="12" {{ $data->records_per_page == 12 ? 'selected' : '' }}>12</option>
                                <option value="18" {{ $data->records_per_page == 18 ? 'selected' : '' }}>18</option>
                                <option value="24" {{ $data->records_per_page == 24 ? 'selected' : '' }}>24</option>
                            </select>
                        </div>

                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       placeholder="Buscar hábitos..."
                                       aria-label="search"
                                       name="filter"
                                       value="{{ $data->filter }}" />
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if($habits->count() > 0)
                    <div class="row g-3">
                        @foreach ($habits as $habit)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm habit-card" style="border-left: 4px solid {{ $habit->color }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="habit-icon me-3" style="background-color: {{ $habit->color }}20;">
                                                    <i class="{{ $habit->icon }}" style="color: {{ $habit->color }};"></i>
                                                </div>
                                                <div>
                                                    <h5 class="card-title mb-0">{{ $habit->name }}</h5>
                                                    <small class="text-muted">
                                                        @if($habit->frequency === 'daily')
                                                            Diario
                                                        @elseif($habit->frequency === 'weekly')
                                                            Semanal
                                                        @else
                                                            Mensual
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            @if(!$habit->active)
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </div>

                                        @if($habit->description)
                                            <p class="text-muted small mb-3">{{ Str::limit($habit->description, 80) }}</p>
                                        @endif

                                        <!-- Progreso de hoy -->
                                        <div class="mb-3">
                                            @php
                                                $todayProgress = $habit->getTodayProgress();
                                                $percentage = ($todayProgress / $habit->goal_count) * 100;
                                            @endphp
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">Progreso de hoy</small>
                                                <strong style="color: {{ $habit->color }}">{{ $todayProgress }}/{{ $habit->goal_count }}</strong>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar"
                                                     role="progressbar"
                                                     style="width: {{ min($percentage, 100) }}%; background-color: {{ $habit->color }};"
                                                     aria-valuenow="{{ $percentage }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Racha actual -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-fire text-warning me-2"></i>
                                                <span class="text-muted">Racha actual: <strong>{{ $habit->getCurrentStreak() }} días</strong></span>
                                            </div>
                                        </div>

                                        <!-- Botón de completar -->
                                        @if($habit->active && \App\Helpers\RoleHelper::isAuthorized('Hábitos.logHabits'))
                                            <button class="btn btn-sm w-100 mb-2 btnCompleteHabit"
                                                    data-habit-id="{{ $habit->id }}"
                                                    data-goal="{{ $habit->goal_count }}"
                                                    data-current="{{ $todayProgress }}"
                                                    style="background-color: {{ $habit->color }}; color: white;">
                                                <i class="bi bi-check2-circle me-1"></i>
                                                @if($habit->isCompletedToday())
                                                    Completado hoy
                                                @else
                                                    Registrar cumplimiento
                                                @endif
                                            </button>
                                        @endif

                                        <!-- Botones de acción -->
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('habits.statistics', $habit->id) }}"
                                               class="btn btn-info btn-sm flex-fill">
                                                <i class="bi bi-graph-up"></i> Stats
                                            </a>

                                            @if (\App\Helpers\RoleHelper::isAuthorized('Hábitos.updateHabits'))
                                                <a href="{{ route('habits.edit', $habit->id) }}"
                                                   class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                            @endif

                                            @if (\App\Helpers\RoleHelper::isAuthorized('Hábitos.deleteHabits'))
                                                <form action="{{ route('habits.delete', $habit->id) }}"
                                                      style="display: contents;"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm btnDelete">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $habits->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-check" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay hábitos registrados</h4>
                        <p class="text-muted">Comienza a construir mejores hábitos hoy</p>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Hábitos.createHabits'))
                            <a href="{{ route('habits.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle-fill me-1"></i>Crear Primer Hábito
                            </a>
                        @endif
                    </div>
                @endif

            </div>

        </div>
    </section>

@endsection

<style>
    .habit-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .habit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .habit-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
</style>

<script type="module">

    $(document).ready(function() {

        // Completar hábito
        $('.btnCompleteHabit').on('click', function() {
            const habitId = $(this).data('habit-id');
            const current = $(this).data('current');
            const goal = $(this).data('goal');
            const button = $(this);

            $.ajax({
                url: '{{ route("habits.logCompletion") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    habit_id: habitId,
                    completion_date: '{{ \Carbon\Carbon::today()->format("Y-m-d") }}',
                    count: current + 1
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: '¡Bien hecho!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Recargar la página para actualizar el progreso
                        setTimeout(() => location.reload(), 2000);
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo registrar el cumplimiento',
                        icon: 'error'
                    });
                }
            });
        });

        // Eliminar hábito
        $('.btnDelete').on('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: "¿Desea eliminar el hábito?",
                text: "Se eliminarán todos los registros asociados",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $(this).closest('form');
                    form.submit();
                }
            });
        });
    });

</script>

@extends('layouts.app')

@section('content')

    <div class="pagetitle">
        <h1>Medicamentos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Medicamentos</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Mis Medicamentos</h3>
                    </div>

                    @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.createMedications'))
                        <div class="col-auto">
                            <a href="{{ route('medications.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill me-1"></i>Nuevo Medicamento
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('medications.index') }}" class="navbar-search" method="GET">
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
                                       placeholder="Buscar medicamentos..."
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

                @if($medications->count() > 0)
                    <div class="row g-3">
                        @foreach ($medications as $medication)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm medication-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0 text-primary">
                                                <i class="bi bi-capsule-pill me-2"></i>{{ $medication->name }}
                                            </h5>
                                            @if($medication->status === 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($medication->status === 'finished')
                                                <span class="badge bg-secondary">Finalizado</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Suspendido</span>
                                            @endif
                                        </div>

                                        <div class="medication-details">
                                            <div class="detail-item mb-2">
                                                <span class="text-muted"><i class="bi bi-prescription2 me-1"></i>Dosis:</span>
                                                <strong>{{ $medication->dosage }}</strong>
                                            </div>

                                            <div class="detail-item mb-2">
                                                <span class="text-muted"><i class="bi bi-clock me-1"></i>Frecuencia:</span>
                                                <strong>{{ $medication->frequency }}</strong>
                                            </div>

                                            <div class="detail-item mb-2">
                                                <span class="text-muted"><i class="bi bi-alarm me-1"></i>Hora:</span>
                                                <strong>{{ $medication->time }}</strong>
                                            </div>

                                            <div class="detail-item mb-2">
                                                <span class="text-muted"><i class="bi bi-calendar-check me-1"></i>Inicio:</span>
                                                <strong>{{ \Carbon\Carbon::parse($medication->start_date)->format('d/m/Y') }}</strong>
                                            </div>

                                            @if($medication->end_date)
                                                <div class="detail-item mb-2">
                                                    <span class="text-muted"><i class="bi bi-calendar-x me-1"></i>Fin:</span>
                                                    <strong>{{ \Carbon\Carbon::parse($medication->end_date)->format('d/m/Y') }}</strong>
                                                </div>
                                            @endif

                                            <div class="detail-item mb-2">
                                                <span class="text-muted"><i class="bi bi-heart-pulse me-1"></i>Vía:</span>
                                                <strong>{{ ucfirst($medication->administration_route) }}</strong>
                                            </div>

                                            <div class="detail-item mb-3">
                                                <span class="text-muted"><i class="bi bi-bell me-1"></i>Recordatorio:</span>
                                                @if((int) $medication->reminder === 1)
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="bi bi-bell-fill"></i> Activado
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="bi bi-bell-slash"></i> Desactivado
                                                    </span>
                                                @endif
                                            </div>

                                            @if($medication->notes)
                                                <div class="alert alert-info py-2 px-3 mb-3">
                                                    <small><strong><i class="bi bi-journal-text me-1"></i>Notas:</strong></small>
                                                    <p class="mb-0 mt-1" style="font-size: 0.85rem;">{{ Str::limit($medication->notes, 100) }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex gap-2 mt-3">
                                            <a href="{{ route('medications.notes', $medication->id) }}"
                                               class="btn btn-info btn-sm flex-fill">
                                                <i class="bi bi-journal-text"></i> Notas
                                                @php
                                                    $notesCount = $medication->notes()->count();
                                                @endphp
                                                ({{ $notesCount }})
                                            </a>

                                            @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.updateMedications'))
                                                <a href="{{ route('medications.edit', $medication->id) }}"
                                                   class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                            @endif

                                            @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.deleteMedications'))
                                                <form action="{{ route('medications.delete', $medication->id) }}"
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
                        {{ $medications->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-capsule" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay medicamentos registrados</h4>
                        <p class="text-muted">Comienza agregando tu primer medicamento</p>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.createMedications'))
                            <a href="{{ route('medications.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle-fill me-1"></i>Nuevo Medicamento
                            </a>
                        @endif
                    </div>
                @endif

            </div>

        </div>
    </section>

@endsection

<style>
    .medication-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }

    .medication-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-item:last-of-type {
        border-bottom: none;
    }

    .bg-success-subtle {
        background-color: #d1e7dd;
    }

    .bg-danger-subtle {
        background-color: #f8d7da;
    }

    .text-success {
        color: #0f5132 !important;
    }

    .text-danger {
        color: #842029 !important;
    }
</style>

<script type="module">

    $(document).ready(function() {

        $('.btnDelete').click(function(event) {

            event.preventDefault();

            Swal.fire({

                title: "¿Desea eliminar el medicamento?",
                text: "No podrá revertirlo",
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

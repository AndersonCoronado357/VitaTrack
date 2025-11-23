@extends('layouts.app')

@section('content')

    <div class="pagetitle">
        <h1>Notas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Notas</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Mis Notas</h3>
                    </div>

                    @if (\App\Helpers\RoleHelper::isAuthorized('Notas.createNotes'))
                        <div class="col-auto">
                            <a href="{{ route('notes.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill me-1"></i>Nueva Nota
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('notes.index') }}" class="navbar-search" method="GET">
                    <div class="row mt-3 mb-4">
                        <div class="col-md-2">
                            <select name="records_per_page" class="form-select" value="{{ $data->records_per_page }}">
                                <option value="5" {{ $data->records_per_page == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $data->records_per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $data->records_per_page == 15 ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $data->records_per_page == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ $data->records_per_page == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>

                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       placeholder="Buscar notas..."
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

                @if($notes->count() > 0)
                    <div class="notes-list">
                        @foreach ($notes as $note)
                            <div class="note-item card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-start">
                                        <div class="col-md-9">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="note-icon me-3">
                                                    <i class="bi bi-journal-text text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-1">
                                                        <a href="{{ route('notes.show', $note->id) }}" class="text-decoration-none text-dark note-title-link">
                                                            {{ $note->title }}
                                                        </a>
                                                    </h5>
                                                    <div class="note-meta">
                                                        <small class="text-muted">
                                                            <a href="{{ route('medications.notes', $note->medication_id) }}"
                                                               class="text-primary text-decoration-none">
                                                                <i class="bi bi-capsule-pill me-1"></i>{{ $note->medication->name }}
                                                            </a>
                                                            <span class="mx-2">•</span>
                                                            <i class="bi bi-calendar-event me-1"></i>{{ $note->created_at->format('d/m/Y') }}
                                                            @if($note->updated_at != $note->created_at)
                                                                <span class="mx-2">•</span>
                                                                <i class="bi bi-pencil me-1"></i>Editada: {{ $note->updated_at->format('d/m/Y') }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="note-preview-text mt-2">
                                                <p class="text-muted mb-0">
                                                    {!! Str::limit(strip_tags($note->content), 150) !!}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('notes.show', $note->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Ver">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                @if (\App\Helpers\RoleHelper::isAuthorized('Notas.updateNotes'))
                                                    <a href="{{ route('notes.edit', $note->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Editar">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                @endif

                                                @if (\App\Helpers\RoleHelper::isAuthorized('Notas.deleteNotes'))
                                                    <form action="{{ route('notes.delete', $note->id) }}"
                                                          style="display: contents;"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger btnDelete"
                                                                title="Eliminar">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notes->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay notas registradas</h4>
                        <p class="text-muted">Comienza creando tu primera nota</p>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Notas.createNotes'))
                            <a href="{{ route('notes.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle-fill me-1"></i>Nueva Nota
                            </a>
                        @endif
                    </div>
                @endif

            </div>

        </div>
    </section>

@endsection

<style>
    .note-item {
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid #0d6efd;
    }

    .note-item:hover {
        transform: translateX(5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .note-icon {
        font-size: 2rem;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e7f1ff;
        border-radius: 10px;
    }

    .note-title-link {
        font-weight: 600;
        transition: color 0.2s;
    }

    .note-title-link:hover {
        color: #0d6efd !important;
    }

    .note-meta {
        line-height: 1.5;
    }

    .note-preview-text {
        padding-left: 66px;
        font-size: 0.9rem;
    }

    .notes-list {
        margin-top: 1rem;
    }
</style>

<script type="module">

    $(document).ready(function() {

        $('.btnDelete').click(function(event) {

            event.preventDefault();

            Swal.fire({

                title: "¿Desea eliminar la nota?",
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

@extends('layouts.app')

@section('content')

    <link href="{{ asset('lib/summernote/summernote-bs5.min.css') }}" rel="stylesheet"/>

    <div class="pagetitle">
        <h1>Notas del Medicamento</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('medications.index') }}">Medicamentos</a></li>
                <li class="breadcrumb-item active">Notas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">
                            <i class="bi bi-capsule-pill me-2"></i>{{ $medication->name }}
                        </h3>
                        <small class="text-muted">Notas asociadas</small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newNoteModal">
                            <i class="bi bi-plus-circle-fill me-1"></i>Nueva Nota
                        </button>
                        <a href="{{ route('medications.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body mt-3">
                @if($notes->count() > 0)
                    <div class="row g-3">
                        @foreach($notes as $note)
                            <div class="col-md-12">
                                <div class="card border-start border-primary border-4 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-journal-text me-2"></i>{{ $note->title }}
                                            </h5>
                                            <div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-sm btn-info btnViewNote"
                                                        data-note-id="{{ $note->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if (\App\Helpers\RoleHelper::isAuthorized('Notas.updateNotes'))
                                                    <button type="button"
                                                            class="btn btn-sm btn-warning btnEditNote"
                                                            data-note-id="{{ $note->id }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                @endif
                                                @if (\App\Helpers\RoleHelper::isAuthorized('Notas.deleteNotes'))
                                                    <form action="{{ route('notes.delete', $note->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger btnDelete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="note-preview">
                                            {!! Str::limit(strip_tags($note->content), 200) !!}
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event"></i> {{ $note->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay notas para este medicamento</h4>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newNoteModal">
                            <i class="bi bi-plus-circle-fill me-1"></i>Crear Primera Nota
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal Nueva Nota -->
    <div class="modal fade" id="newNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('notes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="medication_id" value="{{ $medication->id }}">
                    <input type="hidden" name="from_medication" value="1">

                    <div class="modal-header">
                        <h5 class="modal-title">Nueva Nota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Contenido</label>
                            <textarea name="content" id="content" class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Nota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Nota -->
    <div class="modal fade" id="viewNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewNoteTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="viewNoteContent" class="note-content-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Nota -->
    <div class="modal fade" id="editNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('notes.update') }}" method="POST" id="editNoteForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="note_id" id="edit_note_id">
                    <input type="hidden" name="medication_id" value="{{ $medication->id }}">
                    <input type="hidden" name="from_medication" value="1">

                    <div class="modal-header">
                        <h5 class="modal-title">Editar Nota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Título</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Contenido</label>
                            <textarea name="content" id="edit_content" class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Nota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<style>
    .note-preview {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .note-content-display {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        min-height: 200px;
    }

    .note-content-display img {
        max-width: 100%;
        height: auto;
    }

    .note-content-display table {
        border-collapse: collapse;
        width: 100%;
    }

    .note-content-display table td,
    .note-content-display table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
</style>

<script type="module" src="{{ asset('lib/summernote/summernote-bs5.min.js') }}"></script>
<script type="module" src="{{ asset('lib/summernote/lang/summernote-es-ES.min.js') }}"></script>

<script type="module">
    $(document).ready(function(){
        // Inicializar Summernote para crear nota
        $('#content').summernote({
            lang: 'es-ES',
            placeholder: 'Digite la nota...',
            height: 200
        });

        // Inicializar Summernote para editar nota
        $('#edit_content').summernote({
            lang: 'es-ES',
            placeholder: 'Digite la nota...',
            height: 200
        });

        // Almacenar notas en una variable accesible
        const notesData = @json($notes);

        // Ver nota
        $('.btnViewNote').on('click', function() {
            const noteId = $(this).data('note-id');
            const note = notesData.find(n => n.id === noteId);

            if (note) {
                $('#viewNoteTitle').text(note.title);
                $('#viewNoteContent').html(note.content);

                const modal = new bootstrap.Modal(document.getElementById('viewNoteModal'));
                modal.show();
            }
        });

        // Editar nota
        $('.btnEditNote').on('click', function() {
            const noteId = $(this).data('note-id');
            const note = notesData.find(n => n.id === noteId);

            if (note) {
                $('#edit_note_id').val(note.id);
                $('#edit_title').val(note.title);
                $('#edit_content').summernote('code', note.content);

                const modal = new bootstrap.Modal(document.getElementById('editNoteModal'));
                modal.show();
            }
        });

        // Eliminar nota
        $('.btnDelete').on('click', function(event) {
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

@extends('layouts.app')

@section('content')

    <link href="{{ asset('lib/summernote/summernote-bs5.min.css') }}" rel="stylesheet" />

    <div class="pagetitle">
        <h1>Notas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></li>
                <li class="breadcrumb-item active">Editar Nota</li>
            </ol>
        </nav>
    </div>

    <section class="section">

        <div class="card">
            <div class="card-header">
                <h3>Editar Nota</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('notes.update') }}" class="row g-3 mt-3" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="note_id" value="{{ $note->id }}" />

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Título" name="title"
                                    value="{{ $note->title }}" />
                                <label>Título</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-control" name="medication_id">
                                    @foreach ($medications as $medication)
                                        <option value="{{ $medication->id }}"
                                            {{ $medication->id == $note->medication->id ? 'selected' : '' }}>
                                            {{ $medication->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Medicamento</label>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label>Contenido</label>
                            <textarea name="content" id="content" class="form-control" rows="30">{{ $note->content }}</textarea>
                        </div>

                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('notes.index') }}" class="btn btn-secondary">Volver</a>
                    </div>

                </form>

            </div>

        </div>

    </section>

@endsection

<script type="module" src="{{ asset('lib/summernote/summernote-bs5.min.js') }}"></script>
<script type="module" src="{{ asset('lib/summernote/lang/summernote-es-ES.min.js') }}"></script>

<script type="module">
    $(document).ready(function() {
        $('#content').summernote({
            lang: 'es-ES',
            placeholder: 'Digite la nota...'
        });
    });
</script>

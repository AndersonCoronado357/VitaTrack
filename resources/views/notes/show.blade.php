@extends('layouts.app')

@section('content')

    <div class="pagetitle">
        <h1>Ver Nota</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('notes.index') }}">Notas</a></li>
                <li class="breadcrumb-item active">{{ $note->title }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $note->title }}</h3>
                    <div>
                        @if (\App\Helpers\RoleHelper::isAuthorized('Notas.updateNotes'))
                            <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-fill"></i> Editar
                            </a>
                        @endif
                        <a href="{{ route('notes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body mt-3">
                <div class="mb-3">
                    <strong><i class="bi bi-capsule-pill me-2"></i>Medicamento:</strong>
                    <a href="{{ route('medications.notes', $note->medication_id) }}" class="text-primary">
                        {{ $note->medication->name }}
                    </a>
                </div>

                <hr>

                <div class="note-content">
                    {!! $note->content !!}
                </div>

                <hr class="mt-4">

                <div class="text-muted">
                    <small>
                        <i class="bi bi-calendar-event"></i> Creada: {{ $note->created_at->format('d/m/Y H:i') }}
                        @if($note->updated_at != $note->created_at)
                            | <i class="bi bi-pencil"></i> Actualizada: {{ $note->updated_at->format('d/m/Y H:i') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </section>

@endsection

<style>
    .note-content {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        min-height: 200px;
    }

    .note-content img {
        max-width: 100%;
        height: auto;
    }
</style>

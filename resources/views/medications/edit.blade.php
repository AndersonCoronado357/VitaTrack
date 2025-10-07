@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Editar Medicamento</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('medications.index') }}">Medicamentos</a></li>
                <li class="breadcrumb-item active">Editar Medicamento</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <h3>Editar Medicamento</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('medications.update') }}" class="row g-3" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="medication_id" value="{{ $medication->id }}" />

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="name" placeholder="Nombre del medicamento" value="{{ $medication->name }}" required>
                            <label>Nombre</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="dosage" placeholder="Ej: 500 mg" value="{{ $medication->dosage }}" required>
                            <label>Dosis</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="frequency" placeholder="Ej: cada 8 horas" value="{{ $medication->frequency }}" required>
                            <label>Frecuencia</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="time" class="form-control" name="time" value="{{ $medication->time }}" required>
                            <label>Hora</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="start_date" value="{{ $medication->start_date }}" required>
                            <label>Fecha de inicio</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="end_date" value="{{ $medication->end_date }}">
                            <label>Fecha de fin</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="administration_route" required>
                                <option value="">Seleccione...</option>
                                <option value="oral" {{ $medication->administration_route == 'oral' ? 'selected' : '' }}>Oral</option>
                                <option value="topical" {{ $medication->administration_route == 'topical' ? 'selected' : '' }}>Tópica</option>
                                <option value="injection" {{ $medication->administration_route == 'injection' ? 'selected' : '' }}>Inyección</option>
                                <option value="inhalation" {{ $medication->administration_route == 'inhalation' ? 'selected' : '' }}>Inhalación</option>
                                <option value="other" {{ $medication->administration_route == 'other' ? 'selected' : '' }}>Otra</option>
                            </select>
                            <label>Vía de administración</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="hidden" name="reminder" value="0">
                            <input class="form-check-input" type="checkbox" name="reminder" value="1" id="reminderCheck" {{ $medication->reminder ? 'checked' : '' }}>
                            <label class="form-check-label" for="reminderCheck">
                                Recordatorio activo
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="status" required>
                                <option value="active" {{ $medication->status == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="finished" {{ $medication->status == 'finished' ? 'selected' : '' }}>Finalizado</option>
                                <option value="suspended" {{ $medication->status == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                            <label>Estado</label>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('medications.index') }}" class="btn btn-secondary">Volver</a>
                    </div>

                </form>

            </div>
        </div>
    </section>
@endsection

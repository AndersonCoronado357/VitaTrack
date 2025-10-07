@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Nuevo Medicamento</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('medications.index') }}">Medicamentos</a></li>
                <li class="breadcrumb-item active">Nuevo Medicamento</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <h3>Nuevo Medicamento</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('medications.store') }}" class="row g-3" method="POST">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="name" placeholder="Nombre del medicamento" required>
                            <label>Nombre</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="dosage" placeholder="Ej: 500 mg" required>
                            <label>Dosis</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="frequency" placeholder="Ej: cada 8 horas" required>
                            <label>Frecuencia</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="time" class="form-control" name="time" required>
                            <label>Hora</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="start_date" required>
                            <label>Fecha de inicio</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="end_date">
                            <label>Fecha de fin</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="administration_route" required>
                                <option value="">Seleccione...</option>
                                <option value="oral">Oral</option>
                                <option value="topical">Tópica</option>
                                <option value="injection">Inyección</option>
                                <option value="inhalation">Inhalación</option>
                                <option value="other">Otra</option>
                            </select>
                            <label>Vía de administración</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="hidden" name="reminder" value="0">
                            <input class="form-check-input" type="checkbox" name="reminder" value="1" id="reminderCheck">
                            <label class="form-check-label" for="reminderCheck">
                                Recordatorio activo
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" name="status" required>
                                <option value="active">Activo</option>
                                <option value="finished">Finalizado</option>
                                <option value="suspended">Suspendido</option>
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

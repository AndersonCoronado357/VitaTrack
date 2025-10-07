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
                <div class="row">
                    <div class="col-md-11">
                        <h3>Medicamentos</h3>
                    </div>

                    <div class="col-md-1">
                        <a href="{{ route('medications.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i></a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                @if (Session::has('message'))
                    <div id="alert-message" class="alert alert-{{ Session::get('message.type') }}">
                        {{ Session::get('message.content') }}
                    </div>
                @endif

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dosis</th>
                            <th>Frecuencia</th>
                            <th>Hora</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Vía</th>
                            <th>Recordatorio</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($medications as $medication)
                            <tr>
                                <td>{{ $medication->id }}</td>
                                <td>{{ $medication->name }}</td>
                                <td>{{ $medication->dosage }}</td>
                                <td>{{ $medication->frequency }}</td>
                                <td>{{ $medication->time }}</td>
                                <td>{{ \Carbon\Carbon::parse($medication->start_date)->format('d/m/Y') }}</td>
                                <td>
                                    {{ $medication->end_date ? \Carbon\Carbon::parse($medication->end_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ ucfirst($medication->administration_route) }}</td>
                                <td>
                                    @if((int) $medication->reminder === 1)
                                        <i class="bi bi-bell-fill text-success" title="Recordatorio activado"></i>
                                    @else
                                        <i class="bi bi-bell-slash text-danger" title="Sin recordatorio"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($medication->status === 'active')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif($medication->status === 'finished')
                                        <span class="badge bg-secondary">Finalizado</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Suspendido</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('medications.edit', $medication->id) }}" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></a>

                                    <form action="{{ route('medications.delete', $medication->id) }}" style="display: contents;" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btnDelete"><i class="bi bi-trash-fill"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No hay medicamentos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>
    </section>

@endsection

<script type="module">
    $(document).ready(function() {

        setTimeout(function() {
            $('#alert-message').fadeOut('slow');
        }, 3000);

        $('.btnDelete').click(function(event) {
            event.preventDefault();

            Swal.fire({
                title: "¿Desea eliminar el medicamento?",
                text: "No podrá revertirlo",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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

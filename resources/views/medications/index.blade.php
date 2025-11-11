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

                    @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.createMedications'))
                        <div class="col-md-1">
                            <a href="{{ route('medications.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i></a>
                        </div>
                    @endif

                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('medications.index') }}" class="navbar-search" method="GET">

                    <div class="row mt-3">
                        <div class="col-md-auto">

                            <select name="records_per_page" class="form-select bg-light border-0 small" value="{{ $data->records_per_page }}">
                                <option value="2" {{ $data->records_per_page == 2 ? 'selected' : '' }}>2</option>
                                <option value="10" {{ $data->records_per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $data->records_per_page == 15 ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $data->records_per_page == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ $data->records_per_page == 50 ? 'selected' : '' }}>50</option>
                            </select>

                        </div>

                        <div class="col-md-10">
                            <div class="input-group mb-3">
                                <input type="text"
                                       class="form-control bg-light border-0 small"
                                       placeholder="Buscar..."
                                       aria-label="search"
                                       name="filter"
                                       value="{{ $data->filter }}" />
                            </div>
                        </div>

                        <div class="col-md-auto">
                            <div class="input-group mb-3">
                                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>

                </form>

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Id</th>
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
                        @foreach ($medications as $medication)

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

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.updateMedications'))
                                        <a href="{{ route('medications.edit', $medication->id) }}" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                    @endif

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Medicamentos.deleteMedications'))
                                        <form action="{{ route('medications.delete', $medication->id) }}" style="display: contents;" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btnDelete"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    @endif

                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>

                {{ $medications->appends(request()->except('page'))->links('components.customPagination') }}

            </div>

        </div>
    </section>

@endsection

<script type="module">

    $(document).ready(function() {

        $('.btnDelete').click(function(event) {

            event.preventDefault();

            Swal.fire({

                title: "¿Desea eliminar el medicamento?",
                text: "No podrá revertirlo",
                icon: 'question',
                showCancelButton: true,

              }).then((result) => {

                if (result.isConfirmed) {

                    const form = $(this).closest('form');

                    form.submit();
                }
              });
        });
    });

</script>

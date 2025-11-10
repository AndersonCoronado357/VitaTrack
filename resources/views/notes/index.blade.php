@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Notas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Notas</li>
            </ol>
        </nav>
    </div>

    <section class="section">

        <div class="card">

            <div class="card-header py-3">
                <div class="row">
                    <div class="m-0 font-weight-bold text-primary col-md-11">
                        Notas
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('notes.create') }}" class="btn btn-primary"><i
                                class="bi bi-plus-circle"></i></a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('notes.index') }}" class="navbar-search" method="GET">

                    <div class="row mt-3">
                        <div class="col-md-auto">

                            <select name="records_per_page" class="form-select bg-light border-0 small"
                                value="{{ $data->records_per_page }}">

                                <option value="2" {{ $data->records_per_page == 2 ? 'selected' : '' }}>2</option>
                                <option value="10" {{ $data->records_per_page == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $data->records_per_page == 15 ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $data->records_per_page == 30 ? 'selected' : '' }}>30</option>
                                <option value="50" {{ $data->records_per_page == 50 ? 'selected' : '' }}>50</option>

                            </select>

                        </div>

                        <div class="col-md-10">
                            <div class="input-group-mb-3">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..."
                                    aria-label="search" name="filter" value="{{ $data->filter }}">
                            </div>
                        </div>

                        <div class="col-md-auto">
                            <div class="input-group-append">
                                <button class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

                <table class="table table-bordered">
                    <thead>
                        <th>Id</th>
                        <th>Título</th>
                        <th>Medicamento</th>
                        <th></th>
                    </thead>

                    <tbody>
                        @foreach ($notes as $note)
                            <tr>
                                <td>{{ $note->id }}</td>
                                <td>{{ $note->title }}</td>
                                <td>{{ $note->medication->name }}</td>

                                <td>
                                    <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <form action="{{ route('notes.delete', $note->id) }}" style="display:contents"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm btnDelete"><i
                                                class="bi bi-trash-fill"></i></button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        {{ $notes->appends(request()->except('page'))->links('components.customPagination') }}
                    </ul>
                </nav>

            </div>
        </div>

    </section>
@endsection
<script type="module">

    $(document).ready(function () {

        $('.btnDelete').click(function (event) {

            event.preventDefault();

            Swal.fire({
                title: "¿Desea eliminar la nota?",
                text: "No podrá revertirlo",
                icon: "question",
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

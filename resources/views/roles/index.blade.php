@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Roles</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-11">
                        <h3>Roles</h3>
                    </div>

                    @if (\App\Helpers\RoleHelper::isAuthorized('Roles.createRoles'))
                        <div class="col-md-1">
                            <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i></a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('roles.index') }}" class="navbar-search" method="GET">

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
                            <div class="input-group-mb-3">
                                <input type="text"
                                       class="form-control bg-light border-0 small"
                                       placeholder="Buscar..."
                                       aria-label="search"
                                       name="filter"
                                       value="{{ $data->filter }}">
                            </div>
                        </div>

                        <div class="col-md-auto">

                            <div class="input-group-mb-3">
                                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                    </div>

                </form>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($roles as $role)

                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>

                                    @if (\App\Helpers\RoleHelper::isAuthorized('Roles.updateRoles'))
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></a>
                                    @endif


                                    @if (\App\Helpers\RoleHelper::isAuthorized('Roles.deleteRoles'))
                                        <form action="{{ route('roles.delete', $role->id) }}" style="display: contents;" method="POST">
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

                {{ $roles->appends(request()->except('page'))->links('components.customPagination') }}
            </div>
        </div>
    </section>
@endsection

<script type="module">

    $(document).ready(function() {

        $('.btnDelete').click(function(event) {

            event.preventDefault();

            Swal.fire({

                title: "¿Desea eliminar el rol?",
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

@extends('layouts.app')

@section('content')

    <link href="{{ asset('lib/summernote/summernote-bs5.min.css') }}" rel="stylesheet" />

    <div class="pagetitle">
        <h1>Roles</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"> <a href="{{ route('roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Nuevo Rol</li>
            </ol>
        </nav>
    </div>

    <section class="section">

        {{-- Role --}}
        <div class="card mt-3">
            <div class="card-header">
                <h3>Nuevo Rol</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('roles.store') }}" class="row g-3 mt-3" method="POST" id="frmCreate">
                    @csrf
                    <input type="hidden" name="permissions" id="permissions" />

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Nombre" name="name" />
                                <label>Nombre</label>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        {{-- Permissions --}}
        <div class="card mt-4">
            <div class="card-body">

                <h3 class="card-title">Permisos</h3>

                <div class="row">

                    @foreach ($modules as $key => $module)

                        <div class="col-md-3 mt-3">
                            <h5>{{ $key }}</h5>

                            @foreach ($module as $item)

                                <div class="form-check form-switch">
                                    <input type="checkbox"
                                           class="form-check-input permission"
                                           data-permission-id="{{ $item->id }}"
                                           id="permission_{{ $item->id }}" />

                                    <label for="permission_{{ $item->id }}"
                                           class="form-check-label">
                                           {{ $item->description }}
                                    </label>
                                </div>

                            @endforeach

                        </div>

                    @endforeach

                </div>

            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary" form="frmCreate" id="btnSave">Guardar</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>
        </div>

    </section>

@endsection


<script type="module">

    $(document).ready(function() {

        $('#btnSave').click(function(event) {

            // Permission
            const permissions = $('.permission:checked');

            let permissionIds = [];

            permissions.each(function() {

                const permissionId = $(this).data('permission-id');
                permissionIds.push(permissionId);
            });

            $('#permissions').val(JSON.stringify(permissionIds));
        });
    });

</script>

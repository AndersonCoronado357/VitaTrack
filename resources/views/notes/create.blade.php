@extends('layouts.app')

@section('content')

    <link href="{{ asset('lib/summernote/summernote-bs5.min.css') }}" rel="stylesheet"/>

    <div class="pagetitle">
        <h1>Notas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"> <a href="{{ route('notes.index') }}">Notas</a></li>
                <li class="breadcrumb-item active">Nueva Nota</li>
            </ol>
        </nav>
    </div>

        <section class="section">

        <div class="card">
            <div class="card-header">
                <h3>Nueva Nota</h3>
            </div>

            <div class="card-body mt-3">

                <form action="{{ route('notes.store') }}" class="row g-3 mt-3" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" placeholder="Titulo" name="title"/>
                                <label>Titulo</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-control" name="medication_id">

                                    @foreach ( $medications as $medication )
                                        <option value="{{ $medication->id }}">{{ $medication->name }}</option>
                                    @endforeach

                                </select>
                                <label>Medicamentos</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Contenido</label>
                            <textarea name="content" id="content" class="form-control" rows="15"></textarea>
                        </div>

                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('notes.index') }}" class="btn btn-secondary">Volver</a>
                    </div>

                </form>

            </div>

    </section>

@endsection

<script type="module" src="{{ asset('lib/summernote/summernote-bs5.min.js') }}"></script>
<script type="module" src="{{ asset('lib/summernote/lang/summernote-es-ES.min.js') }}"></script>

<script type="module">

    $(document).ready(function(){

        $('#content').summernote({
            lang: 'es-ES',
            placeholder: 'Digite la nota...'
        });

    })

</script>

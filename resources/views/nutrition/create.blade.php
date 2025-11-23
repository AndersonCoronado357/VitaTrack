@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Registrar Comida</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('nutrition.index') }}">Nutrición</a></li>
                <li class="breadcrumb-item active">Registrar Comida</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h3>Nueva Comida</h3>
            </div>

            <div class="card-body mt-3">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('nutrition.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Tipo de comida *</label>
                        <select class="form-select" name="meal_type" required>
                            <option value="breakfast" {{ old('meal_type', $mealType) == 'breakfast' ? 'selected' : '' }}>🌅 Desayuno</option>
                            <option value="lunch" {{ old('meal_type', $mealType) == 'lunch' ? 'selected' : '' }}>☀️ Almuerzo</option>
                            <option value="dinner" {{ old('meal_type', $mealType) == 'dinner' ? 'selected' : '' }}>🌙 Cena</option>
                            <option value="snack" {{ old('meal_type', $mealType) == 'snack' ? 'selected' : '' }}>🍎 Merienda</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nombre del alimento *</label>
                        <input type="text" class="form-control" name="food_name" value="{{ old('food_name') }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cantidad *</label>
                        <input type="number" step="0.01" class="form-control" name="quantity" value="{{ old('quantity', 100) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Unidad *</label>
                        <select class="form-select" name="unit" required>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gramos (g)</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                            <option value="unidad" {{ old('unit') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                            <option value="porción" {{ old('unit') == 'porción' ? 'selected' : '' }}>Porción</option>
                            <option value="taza" {{ old('unit') == 'taza' ? 'selected' : '' }}>Taza</option>
                            <option value="cucharada" {{ old('unit') == 'cucharada' ? 'selected' : '' }}>Cucharada</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Calorías *</label>
                        <input type="number" class="form-control" name="calories" value="{{ old('calories', 0) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Proteínas (g) *</label>
                        <input type="number" step="0.1" class="form-control" name="proteins" value="{{ old('proteins', 0) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Carbohidratos (g) *</label>
                        <input type="number" step="0.1" class="form-control" name="carbs" value="{{ old('carbs', 0) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Grasas (g) *</label>
                        <input type="number" step="0.1" class="form-control" name="fats" value="{{ old('fats', 0) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Fibra (g)</label>
                        <input type="number" step="0.1" class="form-control" name="fiber" value="{{ old('fiber', 0) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha *</label>
                        <input type="date" class="form-control" name="meal_date" value="{{ old('meal_date', $date) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hora</label>
                        <input type="time" class="form-control" name="meal_time" value="{{ old('meal_time') }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Imagen (opcional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG. Máximo 2MB</small>
                    </div>

                    <div class="col-md-12">
                        <hr>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                        <a href="{{ route('nutrition.index', ['date' => $date]) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

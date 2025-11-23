@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Historial Alimenticio</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('nutrition.index') }}">Nutrición</a></li>
                <li class="breadcrumb-item active">Historial</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-0">Historial de Comidas</h5>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('nutrition.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('nutrition.history') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </form>

                @if($meals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Tipo</th>
                                    <th>Alimento</th>
                                    <th>Cantidad</th>
                                    <th>Calorías</th>
                                    <th>Proteínas</th>
                                    <th>Carbohidratos</th>
                                    <th>Grasas</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meals as $meal)
                                    <tr>
                                        <td>{{ $meal->meal_date->format('d/m/Y') }}</td>
                                        <td>{{ $meal->meal_time ? substr($meal->meal_time, 0, 5) : '-' }}</td>
                                        <td>
                                            @if($meal->meal_type == 'breakfast')
                                                <span class="badge bg-warning">🌅 Desayuno</span>
                                            @elseif($meal->meal_type == 'lunch')
                                                <span class="badge bg-primary">☀️ Almuerzo</span>
                                            @elseif($meal->meal_type == 'dinner')
                                                <span class="badge bg-dark">🌙 Cena</span>
                                            @else
                                                <span class="badge bg-success">🍎 Merienda</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $meal->food_name }}</strong>
                                            @if($meal->description)
                                                <br><small class="text-muted">{{ Str::limit($meal->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $meal->quantity }} {{ $meal->unit }}</td>
                                        <td><span class="badge bg-danger">{{ $meal->calories }} kcal</span></td>
                                        <td>{{ number_format($meal->proteins, 1) }}g</td>
                                        <td>{{ number_format($meal->carbs, 1) }}g</td>
                                        <td>{{ number_format($meal->fats, 1) }}g</td>
                                        <td>
                                            @if (\App\Helpers\RoleHelper::isAuthorized('Nutrición.updateNutrition'))
                                                <a href="{{ route('nutrition.edit', $meal->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $meals->appends(request()->except('page'))->links('components.customPagination') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No hay registros en el rango seleccionado</h4>
                        <p class="text-muted">Prueba con otras fechas</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

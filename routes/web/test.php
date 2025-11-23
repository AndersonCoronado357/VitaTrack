<?php

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/test/create-habit-data', function() {
    if (!Auth::check()) {
        return 'Debes estar autenticado';
    }

    // Crear un hábito de prueba
    $habit = Habit::create([
        'user_id' => Auth::id(),
        'name' => 'Hábito de Prueba',
        'description' => 'Este es un hábito de prueba con datos',
        'frequency' => 'daily',
        'goal_count' => 1,
        'color' => '#ff0000',
        'icon' => 'bi-heart-fill',
        'start_date' => Carbon::today()->subDays(30),
        'active' => true,
    ]);

    // Crear logs para los últimos 30 días
    for ($i = 30; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);

        // 70% de probabilidad de completar
        if (rand(1, 100) <= 70) {
            HabitLog::create([
                'habit_id' => $habit->id,
                'user_id' => Auth::id(),
                'completion_date' => $date,
                'count' => 1,
                'notes' => $i % 7 === 0 ? 'Nota de prueba' : null,
            ]);
        }
    }

    return "Hábito creado con ID: {$habit->id} y " . $habit->logs()->count() . " registros.";
})->middleware('auth');

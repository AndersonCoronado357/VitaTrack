<?php

namespace Database\Seeders;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer usuario (puedes cambiar esto por tu usuario)
        $user = User::first();

        if (!$user) {
            $this->command->error('No hay usuarios en la base de datos');
            return;
        }

        // Crear hábito 1: Ejercicio diario
        $habit1 = Habit::create([
            'user_id' => $user->id,
            'name' => 'Hacer ejercicio',
            'description' => '30 minutos de ejercicio cardiovascular',
            'frequency' => 'daily',
            'goal_count' => 1,
            'reminder_time' => '07:00',
            'color' => '#28a745',
            'icon' => 'bi-bicycle',
            'start_date' => Carbon::today()->subDays(45),
            'active' => true,
        ]);

        // Crear logs para los últimos 45 días con patrón realista
        for ($i = 45; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // 80% de probabilidad de completar
            if (rand(1, 100) <= 80) {
                HabitLog::create([
                    'habit_id' => $habit1->id,
                    'user_id' => $user->id,
                    'completion_date' => $date,
                    'count' => 1,
                    'notes' => $i % 7 === 0 ? 'Buen progreso esta semana' : null,
                ]);
            }
        }

        // Crear hábito 2: Leer
        $habit2 = Habit::create([
            'user_id' => $user->id,
            'name' => 'Leer',
            'description' => 'Leer al menos 20 páginas al día',
            'frequency' => 'daily',
            'goal_count' => 1,
            'reminder_time' => '21:00',
            'color' => '#6f42c1',
            'icon' => 'bi-book',
            'start_date' => Carbon::today()->subDays(30),
            'active' => true,
        ]);

        // Crear logs para hábito de lectura (racha actual)
        for ($i = 10; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            HabitLog::create([
                'habit_id' => $habit2->id,
                'user_id' => $user->id,
                'completion_date' => $date,
                'count' => 1,
                'notes' => null,
            ]);
        }

        // Crear hábito 3: Beber agua
        $habit3 = Habit::create([
            'user_id' => $user->id,
            'name' => 'Beber agua',
            'description' => 'Tomar 8 vasos de agua al día',
            'frequency' => 'daily',
            'goal_count' => 8,
            'reminder_time' => '10:00',
            'color' => '#17a2b8',
            'icon' => 'bi-droplet-fill',
            'start_date' => Carbon::today()->subDays(20),
            'active' => true,
        ]);

        // Crear logs para beber agua con progreso variable
        for ($i = 20; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // Progreso aleatorio entre 4 y 10 vasos
            $count = rand(4, 10);

            HabitLog::create([
                'habit_id' => $habit3->id,
                'user_id' => $user->id,
                'completion_date' => $date,
                'count' => $count,
                'notes' => $count >= 8 ? 'Meta alcanzada' : null,
            ]);
        }

        // Crear hábito 4: Meditar
        $habit4 = Habit::create([
            'user_id' => $user->id,
            'name' => 'Meditar',
            'description' => '10 minutos de meditación matutina',
            'frequency' => 'daily',
            'goal_count' => 1,
            'reminder_time' => '06:30',
            'color' => '#fd7e14',
            'icon' => 'bi-flower1',
            'start_date' => Carbon::today()->subDays(60),
            'active' => true,
        ]);

        // Crear logs con una racha larga y luego ruptura
        for ($i = 60; $i >= 15; $i--) {
            $date = Carbon::today()->subDays($i);

            if ($i > 30 || rand(1, 100) <= 90) {
                HabitLog::create([
                    'habit_id' => $habit4->id,
                    'user_id' => $user->id,
                    'completion_date' => $date,
                    'count' => 1,
                ]);
            }
        }

        // Racha actual de meditación (últimos 10 días)
        for ($i = 10; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            HabitLog::create([
                'habit_id' => $habit4->id,
                'user_id' => $user->id,
                'completion_date' => $date,
                'count' => 1,
            ]);
        }

        // Crear hábito 5: Inactivo
        Habit::create([
            'user_id' => $user->id,
            'name' => 'Correr 5km',
            'description' => 'Pausado por lesión',
            'frequency' => 'daily',
            'goal_count' => 1,
            'reminder_time' => '06:00',
            'color' => '#dc3545',
            'icon' => 'bi-lightning-fill',
            'start_date' => Carbon::today()->subDays(90),
            'end_date' => Carbon::today()->subDays(10),
            'active' => false,
        ]);

        $this->command->info('✓ Hábitos de prueba creados exitosamente');
        $this->command->info('✓ Se crearon 5 hábitos con datos históricos');
        $this->command->info('✓ Usuario: ' . $user->email);
    }
}

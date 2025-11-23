<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\NutritionGoal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NutritionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer usuario
        $user = User::first();

        if (!$user) {
            $this->command->error('No hay usuarios en la base de datos');
            return;
        }

        // Crear metas nutricionales para el usuario
        NutritionGoal::updateOrCreate(
            ['user_id' => $user->id],
            [
                'daily_calories_goal' => 2000,
                'daily_proteins_goal' => 150,
                'daily_carbs_goal' => 200,
                'daily_fats_goal' => 65,
                'daily_fiber_goal' => 30,
                'daily_water_goal' => 2500
            ]
        );

        // Alimentos de ejemplo por tipo de comida
        $breakfastFoods = [
            ['name' => 'Avena con frutas', 'calories' => 350, 'proteins' => 12, 'carbs' => 60, 'fats' => 8, 'fiber' => 10, 'quantity' => 200, 'unit' => 'g'],
            ['name' => 'Huevos revueltos con tostadas', 'calories' => 420, 'proteins' => 25, 'carbs' => 35, 'fats' => 18, 'fiber' => 4, 'quantity' => 1, 'unit' => 'porción'],
            ['name' => 'Yogurt griego con granola', 'calories' => 320, 'proteins' => 20, 'carbs' => 40, 'fats' => 9, 'fiber' => 5, 'quantity' => 250, 'unit' => 'g'],
            ['name' => 'Batido de proteína con banana', 'calories' => 380, 'proteins' => 30, 'carbs' => 45, 'fats' => 8, 'fiber' => 6, 'quantity' => 300, 'unit' => 'ml'],
            ['name' => 'Pan integral con aguacate', 'calories' => 340, 'proteins' => 10, 'carbs' => 38, 'fats' => 16, 'fiber' => 12, 'quantity' => 150, 'unit' => 'g'],
            ['name' => 'Pancakes de avena', 'calories' => 400, 'proteins' => 15, 'carbs' => 55, 'fats' => 12, 'fiber' => 8, 'quantity' => 3, 'unit' => 'unidad'],
        ];

        $lunchFoods = [
            ['name' => 'Pechuga de pollo con arroz integral', 'calories' => 550, 'proteins' => 45, 'carbs' => 60, 'fats' => 12, 'fiber' => 8, 'quantity' => 350, 'unit' => 'g'],
            ['name' => 'Salmón a la plancha con vegetales', 'calories' => 480, 'proteins' => 40, 'carbs' => 25, 'fats' => 22, 'fiber' => 6, 'quantity' => 300, 'unit' => 'g'],
            ['name' => 'Ensalada César con pollo', 'calories' => 420, 'proteins' => 35, 'carbs' => 28, 'fats' => 18, 'fiber' => 5, 'quantity' => 400, 'unit' => 'g'],
            ['name' => 'Pasta integral con atún', 'calories' => 520, 'proteins' => 38, 'carbs' => 65, 'fats' => 10, 'fiber' => 9, 'quantity' => 350, 'unit' => 'g'],
            ['name' => 'Tacos de carne molida', 'calories' => 580, 'proteins' => 42, 'carbs' => 55, 'fats' => 20, 'fiber' => 7, 'quantity' => 3, 'unit' => 'unidad'],
            ['name' => 'Bowl de quinoa con vegetales', 'calories' => 460, 'proteins' => 18, 'carbs' => 70, 'fats' => 12, 'fiber' => 12, 'quantity' => 400, 'unit' => 'g'],
        ];

        $dinnerFoods = [
            ['name' => 'Pechuga de pavo con ensalada', 'calories' => 380, 'proteins' => 38, 'carbs' => 25, 'fats' => 12, 'fiber' => 6, 'quantity' => 300, 'unit' => 'g'],
            ['name' => 'Filete de pescado con brócoli', 'calories' => 320, 'proteins' => 35, 'carbs' => 18, 'fats' => 10, 'fiber' => 5, 'quantity' => 250, 'unit' => 'g'],
            ['name' => 'Sopa de lentejas', 'calories' => 340, 'proteins' => 20, 'carbs' => 50, 'fats' => 6, 'fiber' => 15, 'quantity' => 400, 'unit' => 'ml'],
            ['name' => 'Tortilla de espinacas', 'calories' => 290, 'proteins' => 22, 'carbs' => 12, 'fats' => 18, 'fiber' => 4, 'quantity' => 1, 'unit' => 'porción'],
            ['name' => 'Pollo al horno con vegetales', 'calories' => 420, 'proteins' => 40, 'carbs' => 30, 'fats' => 14, 'fiber' => 8, 'quantity' => 350, 'unit' => 'g'],
            ['name' => 'Wrap de atún con verduras', 'calories' => 360, 'proteins' => 28, 'carbs' => 38, 'fats' => 10, 'fiber' => 6, 'quantity' => 1, 'unit' => 'unidad'],
        ];

        $snackFoods = [
            ['name' => 'Almendras', 'calories' => 180, 'proteins' => 6, 'carbs' => 8, 'fats' => 14, 'fiber' => 4, 'quantity' => 30, 'unit' => 'g'],
            ['name' => 'Manzana con mantequilla de maní', 'calories' => 220, 'proteins' => 8, 'carbs' => 28, 'fats' => 10, 'fiber' => 5, 'quantity' => 1, 'unit' => 'porción'],
            ['name' => 'Batido de frutas', 'calories' => 160, 'proteins' => 4, 'carbs' => 35, 'fats' => 2, 'fiber' => 4, 'quantity' => 250, 'unit' => 'ml'],
            ['name' => 'Barra de proteína', 'calories' => 200, 'proteins' => 20, 'carbs' => 22, 'fats' => 6, 'fiber' => 3, 'quantity' => 1, 'unit' => 'unidad'],
            ['name' => 'Yogurt natural', 'calories' => 150, 'proteins' => 12, 'carbs' => 18, 'fats' => 4, 'fiber' => 0, 'quantity' => 200, 'unit' => 'g'],
            ['name' => 'Galletas integrales con queso', 'calories' => 190, 'proteins' => 8, 'carbs' => 24, 'fats' => 8, 'fiber' => 3, 'quantity' => 1, 'unit' => 'porción'],
            ['name' => 'Plátano', 'calories' => 105, 'proteins' => 1, 'carbs' => 27, 'fats' => 0, 'fiber' => 3, 'quantity' => 1, 'unit' => 'unidad'],
        ];

        // Descripciones opcionales
        $descriptions = [
            'Con leche descremada',
            'Preparado en casa',
            'Orgánico',
            'Sin azúcar añadida',
            'Bajo en sodio',
            'Receta casera',
            null,
            null,
            null,
        ];

        $mealsCreated = 0;

        // Generar registros para los últimos 45 días
        for ($i = 45; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // 85% de probabilidad de tener comidas ese día
            if (rand(1, 100) <= 85) {

                // Desayuno (95% de probabilidad)
                if (rand(1, 100) <= 95) {
                    $food = $breakfastFoods[array_rand($breakfastFoods)];
                    Meal::create([
                        'user_id' => $user->id,
                        'meal_type' => 'breakfast',
                        'food_name' => $food['name'],
                        'description' => $descriptions[array_rand($descriptions)],
                        'quantity' => $food['quantity'],
                        'unit' => $food['unit'],
                        'calories' => $food['calories'],
                        'proteins' => $food['proteins'],
                        'carbs' => $food['carbs'],
                        'fats' => $food['fats'],
                        'fiber' => $food['fiber'],
                        'meal_date' => $date,
                        'meal_time' => '07:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    ]);
                    $mealsCreated++;
                }

                // Snack mañana (40% de probabilidad)
                if (rand(1, 100) <= 40) {
                    $food = $snackFoods[array_rand($snackFoods)];
                    Meal::create([
                        'user_id' => $user->id,
                        'meal_type' => 'snack',
                        'food_name' => $food['name'],
                        'description' => 'Media mañana',
                        'quantity' => $food['quantity'],
                        'unit' => $food['unit'],
                        'calories' => $food['calories'],
                        'proteins' => $food['proteins'],
                        'carbs' => $food['carbs'],
                        'fats' => $food['fats'],
                        'fiber' => $food['fiber'],
                        'meal_date' => $date,
                        'meal_time' => '10:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    ]);
                    $mealsCreated++;
                }

                // Almuerzo (98% de probabilidad)
                if (rand(1, 100) <= 98) {
                    $food = $lunchFoods[array_rand($lunchFoods)];
                    Meal::create([
                        'user_id' => $user->id,
                        'meal_type' => 'lunch',
                        'food_name' => $food['name'],
                        'description' => $descriptions[array_rand($descriptions)],
                        'quantity' => $food['quantity'],
                        'unit' => $food['unit'],
                        'calories' => $food['calories'],
                        'proteins' => $food['proteins'],
                        'carbs' => $food['carbs'],
                        'fats' => $food['fats'],
                        'fiber' => $food['fiber'],
                        'meal_date' => $date,
                        'meal_time' => '13:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    ]);
                    $mealsCreated++;
                }

                // Snack tarde (60% de probabilidad)
                if (rand(1, 100) <= 60) {
                    $food = $snackFoods[array_rand($snackFoods)];
                    Meal::create([
                        'user_id' => $user->id,
                        'meal_type' => 'snack',
                        'food_name' => $food['name'],
                        'description' => 'Media tarde',
                        'quantity' => $food['quantity'],
                        'unit' => $food['unit'],
                        'calories' => $food['calories'],
                        'proteins' => $food['proteins'],
                        'carbs' => $food['carbs'],
                        'fats' => $food['fats'],
                        'fiber' => $food['fiber'],
                        'meal_date' => $date,
                        'meal_time' => '16:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    ]);
                    $mealsCreated++;
                }

                // Cena (90% de probabilidad)
                if (rand(1, 100) <= 90) {
                    $food = $dinnerFoods[array_rand($dinnerFoods)];
                    Meal::create([
                        'user_id' => $user->id,
                        'meal_type' => 'dinner',
                        'food_name' => $food['name'],
                        'description' => $descriptions[array_rand($descriptions)],
                        'quantity' => $food['quantity'],
                        'unit' => $food['unit'],
                        'calories' => $food['calories'],
                        'proteins' => $food['proteins'],
                        'carbs' => $food['carbs'],
                        'fats' => $food['fats'],
                        'fiber' => $food['fiber'],
                        'meal_date' => $date,
                        'meal_time' => '19:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    ]);
                    $mealsCreated++;
                }

                // Snack noche (30% de probabilidad)
                if (rand(1, 100) <= 30) {
                    $food = $snackFoods[array_rand($snackFoods)];
                    Meal::create([
                        'user_id' => $user->id,
                        'meal_type' => 'snack',
                        'food_name' => $food['name'],
                        'description' => 'Antes de dormir',
                        'quantity' => $food['quantity'],
                        'unit' => $food['unit'],
                        'calories' => $food['calories'],
                        'proteins' => $food['proteins'],
                        'carbs' => $food['carbs'],
                        'fats' => $food['fats'],
                        'fiber' => $food['fiber'],
                        'meal_date' => $date,
                        'meal_time' => '21:' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT),
                    ]);
                    $mealsCreated++;
                }
            }
        }

        $this->command->info('✓ Metas nutricionales creadas para el usuario: ' . $user->email);
        $this->command->info('✓ Se crearon ' . $mealsCreated . ' registros de comidas');
        $this->command->info('✓ Período: últimos 45 días con variación realista');
    }
}

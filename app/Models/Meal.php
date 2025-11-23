<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meal extends Model
{
    protected $fillable = [
        'user_id',
        'meal_type',
        'food_name',
        'description',
        'quantity',
        'unit',
        'calories',
        'proteins',
        'carbs',
        'fats',
        'fiber',
        'meal_date',
        'meal_time',
        'image_path'
    ];

    protected $casts = [
        'meal_date' => 'date',
        'quantity' => 'decimal:2',
        'proteins' => 'decimal:2',
        'carbs' => 'decimal:2',
        'fats' => 'decimal:2',
        'fiber' => 'decimal:2',
        'calories' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMealTypeNameAttribute()
    {
        $types = [
            'breakfast' => 'Desayuno',
            'lunch' => 'Almuerzo',
            'dinner' => 'Cena',
            'snack' => 'Merienda'
        ];

        return $types[$this->meal_type] ?? $this->meal_type;
    }

    // Obtener total de calorías por fecha
    public static function getTotalCaloriesByDate($userId, $date)
    {
        return self::where('user_id', $userId)
            ->where('meal_date', $date)
            ->sum('calories');
    }

    // Obtener totales de macros por fecha
    public static function getMacrosByDate($userId, $date)
    {
        return self::where('user_id', $userId)
            ->where('meal_date', $date)
            ->selectRaw('
                SUM(calories) as total_calories,
                SUM(proteins) as total_proteins,
                SUM(carbs) as total_carbs,
                SUM(fats) as total_fats,
                SUM(fiber) as total_fiber
            ')
            ->first();
    }
}

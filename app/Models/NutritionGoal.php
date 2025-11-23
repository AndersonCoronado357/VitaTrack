<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionGoal extends Model
{
    protected $fillable = [
        'user_id',
        'daily_calories_goal',
        'daily_proteins_goal',
        'daily_carbs_goal',
        'daily_fats_goal',
        'daily_fiber_goal',
        'daily_water_goal'
    ];

    protected $casts = [
        'daily_calories_goal' => 'integer',
        'daily_proteins_goal' => 'decimal:2',
        'daily_carbs_goal' => 'decimal:2',
        'daily_fats_goal' => 'decimal:2',
        'daily_fiber_goal' => 'decimal:2',
        'daily_water_goal' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Obtener o crear metas para un usuario
    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'daily_calories_goal' => 2000,
                'daily_proteins_goal' => 50,
                'daily_carbs_goal' => 250,
                'daily_fats_goal' => 70,
                'daily_fiber_goal' => 25,
                'daily_water_goal' => 2000
            ]
        );
    }
}

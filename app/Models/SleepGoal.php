<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SleepGoal extends Model
{
    protected $fillable = [
        'user_id',
        'target_hours',
        'target_bedtime',
        'target_wake_time',
        'max_interruptions'
    ];

    protected $casts = [
        'target_hours' => 'decimal:2',
        'max_interruptions' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Obtener o crear meta para un usuario
    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'target_hours' => 8.0,
                'target_bedtime' => '22:00',
                'target_wake_time' => '06:00',
                'max_interruptions' => 2
            ]
        );
    }
}

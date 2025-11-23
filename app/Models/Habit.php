<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Habit extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'frequency',
        'goal_count',
        'reminder_time',
        'color',
        'icon',
        'start_date',
        'end_date',
        'active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
        'goal_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    // Obtener el progreso de hoy
    public function getTodayProgress()
    {
        $today = Carbon::today();
        $log = $this->logs()->where('completion_date', $today)->first();

        return $log ? $log->count : 0;
    }

    // Verificar si se completó hoy
    public function isCompletedToday()
    {
        return $this->getTodayProgress() >= $this->goal_count;
    }

    // Calcular racha actual
    public function getCurrentStreak()
    {
        $streak = 0;
        $date = Carbon::yesterday();

        while (true) {
            $log = $this->logs()->where('completion_date', $date)->first();

            if (!$log || $log->count < $this->goal_count) {
                break;
            }

            $streak++;
            $date->subDay();
        }

        return $streak;
    }

    // Calcular tasa de cumplimiento
    public function getCompletionRate($days = 30)
    {
        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        $totalDays = $days;
        $completedDays = $this->logs()
            ->whereBetween('completion_date', [$startDate, $endDate])
            ->where('count', '>=', $this->goal_count)
            ->count();

        return $totalDays > 0 ? round(($completedDays / $totalDays) * 100, 1) : 0;
    }
}

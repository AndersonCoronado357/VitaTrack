<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SleepRecord extends Model
{
    protected $fillable = [
        'user_id',
        'sleep_date',
        'bedtime',
        'wake_time',
        'total_hours',
        'interruptions',
        'quality',
        'felt_rested',
        'notes'
    ];

    protected $casts = [
        'sleep_date' => 'date',
        'total_hours' => 'decimal:2',
        'interruptions' => 'integer',
        'felt_rested' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getQualityTextAttribute()
    {
        $qualities = [
            'excellent' => 'Excelente',
            'good' => 'Buena',
            'fair' => 'Regular',
            'poor' => 'Mala'
        ];

        return $qualities[$this->quality] ?? 'N/A';
    }

    public function getQualityColorAttribute()
    {
        $colors = [
            'excellent' => 'success',
            'good' => 'primary',
            'fair' => 'warning',
            'poor' => 'danger'
        ];

        return $colors[$this->quality] ?? 'secondary';
    }

    public function getQualityIconAttribute()
    {
        $icons = [
            'excellent' => 'bi-star-fill',
            'good' => 'bi-emoji-smile',
            'fair' => 'bi-emoji-neutral',
            'poor' => 'bi-emoji-frown'
        ];

        return $icons[$this->quality] ?? 'bi-moon';
    }

    // Calcular horas totales desde bedtime y wake_time
    public static function calculateTotalHours($bedtime, $wakeTime)
    {
        $bed = Carbon::parse($bedtime);
        $wake = Carbon::parse($wakeTime);

        // Si wake_time es menor que bedtime, significa que despertó al día siguiente
        if ($wake->lessThan($bed)) {
            $wake->addDay();
        }

        return $bed->diffInMinutes($wake) / 60;
    }

    // Determinar calidad automática basada en horas y interrupciones
    public static function determineQuality($totalHours, $interruptions, $targetHours = 8)
    {
        $hoursDiff = abs($totalHours - $targetHours);

        if ($totalHours >= ($targetHours - 0.5) && $totalHours <= ($targetHours + 1) && $interruptions <= 1) {
            return 'excellent';
        } elseif ($totalHours >= ($targetHours - 1) && $totalHours <= ($targetHours + 2) && $interruptions <= 2) {
            return 'good';
        } elseif ($totalHours >= ($targetHours - 2) && $totalHours <= ($targetHours + 3) && $interruptions <= 3) {
            return 'fair';
        }

        return 'poor';
    }

    // Obtener promedio de horas de sueño en un período
    public static function getAverageSleep($userId, $days = 7)
    {
        return self::where('user_id', $userId)
            ->where('sleep_date', '>=', Carbon::today()->subDays($days))
            ->avg('total_hours');
    }

    // Obtener estadísticas de calidad
    public static function getQualityStats($userId, $days = 30)
    {
        $records = self::where('user_id', $userId)
            ->where('sleep_date', '>=', Carbon::today()->subDays($days))
            ->get();

        return [
            'total' => $records->count(),
            'excellent' => $records->where('quality', 'excellent')->count(),
            'good' => $records->where('quality', 'good')->count(),
            'fair' => $records->where('quality', 'fair')->count(),
            'poor' => $records->where('quality', 'poor')->count(),
        ];
    }
}

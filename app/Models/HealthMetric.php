<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HealthMetric extends Model
{
    protected $fillable = [
        'user_id',
        'metric_type',
        'value',
        'value_secondary',
        'unit',
        'measured_date',
        'measured_time',
        'notes',
        'is_fasting',
        'status'
    ];

    protected $casts = [
        'measured_date' => 'date',
        'value' => 'decimal:2',
        'value_secondary' => 'decimal:2',
        'is_fasting' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMetricTypeNameAttribute()
    {
        $types = [
            'blood_pressure' => 'Presión Arterial',
            'glucose' => 'Glucosa',
            'weight' => 'Peso',
            'heart_rate' => 'Frecuencia Cardíaca',
            'temperature' => 'Temperatura',
            'oxygen' => 'Oxígeno en Sangre',
            'cholesterol' => 'Colesterol'
        ];

        return $types[$this->metric_type] ?? $this->metric_type;
    }

    public function getMetricTypeIconAttribute()
    {
        $icons = [
            'blood_pressure' => 'bi-heart-pulse',
            'glucose' => 'bi-droplet-half',
            'weight' => 'bi-speedometer2',
            'heart_rate' => 'bi-activity',
            'temperature' => 'bi-thermometer-half',
            'oxygen' => 'bi-lungs',
            'cholesterol' => 'bi-clipboard-pulse'
        ];

        return $icons[$this->metric_type] ?? 'bi-heart';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'normal' => 'success',
            'warning' => 'warning',
            'alert' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'normal' => 'Normal',
            'warning' => 'Atención',
            'alert' => 'Alerta'
        ];

        return $texts[$this->status] ?? 'N/A';
    }

    public function getFormattedValueAttribute()
    {
        if ($this->metric_type === 'blood_pressure' && $this->value_secondary) {
            return $this->value . '/' . $this->value_secondary . ' ' . $this->unit;
        }
        return $this->value . ' ' . $this->unit;
    }

    // Determinar el estado basado en rangos
    public static function determineStatus($metricType, $value, $valueSecondary = null, $userId = null)
    {
        $ranges = self::getDefaultRanges($metricType);

        // Si el usuario tiene rangos personalizados, usarlos
        if ($userId) {
            $customRange = HealthMetricRange::where('user_id', $userId)
                ->where('metric_type', $metricType)
                ->first();

            if ($customRange) {
                $ranges = [
                    'min_normal' => $customRange->min_normal,
                    'max_normal' => $customRange->max_normal,
                    'min_warning' => $customRange->min_warning,
                    'max_warning' => $customRange->max_warning,
                    'min_normal_secondary' => $customRange->min_normal_secondary,
                    'max_normal_secondary' => $customRange->max_normal_secondary,
                ];
            }
        }

        // Presión arterial (requiere ambos valores)
        if ($metricType === 'blood_pressure' && $valueSecondary) {
            $systolicNormal = $value >= $ranges['min_normal'] && $value <= $ranges['max_normal'];
            $diastolicNormal = $valueSecondary >= $ranges['min_normal_secondary'] && $valueSecondary <= $ranges['max_normal_secondary'];

            if ($systolicNormal && $diastolicNormal) {
                return 'normal';
            }

            $systolicAlert = $value < $ranges['min_warning'] || $value > $ranges['max_warning'];
            $diastolicAlert = $valueSecondary < 50 || $valueSecondary > 100;

            if ($systolicAlert || $diastolicAlert) {
                return 'alert';
            }

            return 'warning';
        }

        // Otras métricas
        if ($value >= $ranges['min_normal'] && $value <= $ranges['max_normal']) {
            return 'normal';
        }

        if ($value < $ranges['min_warning'] || $value > $ranges['max_warning']) {
            return 'alert';
        }

        return 'warning';
    }

    // Rangos por defecto para cada métrica
    public static function getDefaultRanges($metricType)
    {
        $ranges = [
            'blood_pressure' => [
                'min_normal' => 90,
                'max_normal' => 120,
                'min_warning' => 70,
                'max_warning' => 140,
                'min_normal_secondary' => 60,
                'max_normal_secondary' => 80,
            ],
            'glucose' => [
                'min_normal' => 70,
                'max_normal' => 100, // en ayunas
                'min_warning' => 50,
                'max_warning' => 140,
            ],
            'weight' => [
                'min_normal' => 50,
                'max_normal' => 100,
                'min_warning' => 40,
                'max_warning' => 150,
            ],
            'heart_rate' => [
                'min_normal' => 60,
                'max_normal' => 100,
                'min_warning' => 40,
                'max_warning' => 120,
            ],
            'temperature' => [
                'min_normal' => 36.1,
                'max_normal' => 37.2,
                'min_warning' => 35.0,
                'max_warning' => 38.5,
            ],
            'oxygen' => [
                'min_normal' => 95,
                'max_normal' => 100,
                'min_warning' => 90,
                'max_warning' => 100,
            ],
            'cholesterol' => [
                'min_normal' => 0,
                'max_normal' => 200,
                'min_warning' => 0,
                'max_warning' => 240,
            ],
        ];

        return $ranges[$metricType] ?? [
            'min_normal' => 0,
            'max_normal' => 100,
            'min_warning' => 0,
            'max_warning' => 150,
        ];
    }
}

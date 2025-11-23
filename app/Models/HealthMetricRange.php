<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthMetricRange extends Model
{
    protected $fillable = [
        'user_id',
        'metric_type',
        'min_normal',
        'max_normal',
        'min_warning',
        'max_warning',
        'min_normal_secondary',
        'max_normal_secondary'
    ];

    protected $casts = [
        'min_normal' => 'decimal:2',
        'max_normal' => 'decimal:2',
        'min_warning' => 'decimal:2',
        'max_warning' => 'decimal:2',
        'min_normal_secondary' => 'decimal:2',
        'max_normal_secondary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

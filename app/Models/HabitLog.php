<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    protected $fillable = [
        'habit_id',
        'user_id',
        'completion_date',
        'count',
        'notes'
    ];

    protected $casts = [
        'completion_date' => 'date',
        'count' => 'integer'
    ];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

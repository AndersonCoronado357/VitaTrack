<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medications extends Model
{
    protected $table = 'Medications';

    protected $fillable = [
        'user_id',
        'name',
        'dosage',
        'frequency',
        'time',
        'start_date',
        'end_date',
        'administration_route',
        'reminder',
        'notes',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'medication_id');
    }
}

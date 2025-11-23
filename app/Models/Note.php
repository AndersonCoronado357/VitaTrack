<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title',
        'content',
        'medication_id',
        'user_id'
    ];

    public function medication()
    {
        return $this->belongsTo(Medications::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

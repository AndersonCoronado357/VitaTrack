<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $appends = ['full_name'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function medications()
    {
        return $this->hasMany(Medications::class);
    }

    public function role() {

        return $this->belongsTo(Role::class);
    }

    public function getFullNameAttribute() {

        return "$this->first_name $this->last_name";
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function habitLogs()
    {
        return $this->hasMany(HabitLog::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function nutritionGoal()
    {
        return $this->hasOne(NutritionGoal::class);
    }

    public function healthMetrics()
    {
        return $this->hasMany(HealthMetric::class);
    }

    public function healthMetricRanges()
    {
        return $this->hasMany(HealthMetricRange::class);
    }

    public function sleepRecords()
    {
        return $this->hasMany(SleepRecord::class);
    }

    public function sleepGoal()
    {
        return $this->hasOne(SleepGoal::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

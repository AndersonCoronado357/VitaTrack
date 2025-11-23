<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'location',
        'appointment_date',
        'appointment_time',
        'duration',
        'doctor_name',
        'specialty',
        'status',
        'reminder_enabled',
        'reminder_minutes',
        'notes',
        'color'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'duration' => 'integer',
        'reminder_enabled' => 'boolean',
        'reminder_minutes' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeNameAttribute()
    {
        $types = [
            'medical' => 'Médica',
            'personal' => 'Personal',
            'work' => 'Trabajo',
            'other' => 'Otro'
        ];

        return $types[$this->type] ?? 'Otro';
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'medical' => 'bi-heart-pulse',
            'personal' => 'bi-person',
            'work' => 'bi-briefcase',
            'other' => 'bi-calendar-event'
        ];

        return $icons[$this->type] ?? 'bi-calendar-event';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'scheduled' => 'Programada',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            'rescheduled' => 'Reprogramada'
        ];

        return $statuses[$this->status] ?? 'N/A';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'scheduled' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'rescheduled' => 'warning'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getEndTimeAttribute()
    {
        $time = Carbon::parse($this->appointment_time);
        return $time->addMinutes($this->duration)->format('H:i');
    }

    public function getReminderTimeAttribute()
    {
        $dateTime = Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
        return $dateTime->subMinutes($this->reminder_minutes);
    }

    public function getIsUpcomingAttribute()
    {
        $dateTime = Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
        return $dateTime->isFuture() && $this->status === 'scheduled';
    }

    public function getIsPastAttribute()
    {
        $dateTime = Carbon::parse($this->appointment_date . ' ' . $this->appointment_time);
        return $dateTime->isPast();
    }

    // Obtener citas del día
    public static function getTodayAppointments($userId)
    {
        return self::where('user_id', $userId)
            ->where('appointment_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_time', 'asc')
            ->get();
    }

    // Obtener próximas citas
    public static function getUpcomingAppointments($userId, $days = 7)
    {
        return self::where('user_id', $userId)
            ->where('appointment_date', '>=', Carbon::today())
            ->where('appointment_date', '<=', Carbon::today()->addDays($days))
            ->where('status', 'scheduled')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();
    }

    // Obtener citas del mes
    public static function getMonthAppointments($userId, $year, $month)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        return self::where('user_id', $userId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();
    }
}

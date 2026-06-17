<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventParticipation extends Model
{
    protected $table = 'event_participations';
    
    protected $fillable = [
        'event_id', 'member_id', 'attendance_status', 'arrival_time', 'notes',
        'transport_provided', 'meal_provided'
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'transport_provided' => 'boolean',
        'meal_provided' => 'boolean'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public static function getAttendanceStatuses()
    {
        return [
            'confirmed' => 'Confirmé',
            'present' => 'Présent',
            'absent' => 'Absent',
            'late' => 'En retard',
            'excused' => 'Excusé'
        ];
    }
}
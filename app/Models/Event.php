<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $table = 'events';
    
    protected $fillable = [
        'title', 'description', 'type', 'start_date', 'end_date',
        'location', 'location_url', 'responsible_id', 'budget',
        'actual_cost', 'status', 'notes', 'poster_path'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2'
    ];

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    public function participants()
    {
        return $this->belongsToMany(Member::class, 'event_participations')
            ->withPivot('attendance_status', 'arrival_time', 'notes')
            ->withTimestamps();
    }

    public function getPosterUrlAttribute(): ?string
    {
        return $this->poster_path ? Storage::url($this->poster_path) : null;
    }

    public function getDurationAttribute(): string
    {
        $diff = $this->start_date->diff($this->end_date);
        return $diff->format('%h h %i min');
    }

    public function getParticipantCountAttribute(): int
    {
        return $this->participations()->count();
    }

    public function getPresentCountAttribute(): int
    {
        return $this->participations()->where('attendance_status', 'present')->count();
    }

    public function getAbsentCountAttribute(): int
    {
        return $this->participations()->where('attendance_status', 'absent')->count();
    }

    public function getLateCountAttribute(): int
    {
        return $this->participations()->where('attendance_status', 'late')->count();
    }

    public function getParticipationRateAttribute(): float
    {
        if ($this->participant_count === 0) return 0;
        return ($this->present_count / $this->participant_count) * 100;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
            ->whereIn('status', ['planned', 'upcoming']);
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', 'ongoing');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public static function getTypes()
    {
        return [
            'concert' => 'Concert',
            'entertainment' => 'Animation',
            'official_outing' => 'Sortie officielle',
            'rehearsal' => 'Répétition',
            'evangelical_campaign' => 'Campagne évangélique',
            'special' => 'Événement spécial',
            'wedding' => 'Mariage',
            'funeral' => 'Obsèques',
            'festival' => 'Festival'
        ];
    }

    public static function getStatuses()
    {
        return [
            'planned' => 'Planifié',
            'upcoming' => 'À venir',
            'ongoing' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé'
        ];
    }
}
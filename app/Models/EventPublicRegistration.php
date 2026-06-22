<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPublicRegistration extends Model
{
    protected $table = 'event_public_registrations';

    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'whatsapp_number',
        'photo_path',
        'status',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}


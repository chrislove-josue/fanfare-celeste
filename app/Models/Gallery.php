<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $table = 'galleries';
    
    protected $fillable = [
        'title', 'type', 'file_path', 'thumbnail_path', 'description',
        'event_date', 'event_id', 'views', 'likes'
    ];

    protected $casts = [
        'event_date' => 'date',
        'views' => 'integer',
        'likes' => 'integer'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null;
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function incrementLikes()
    {
        $this->increment('likes');
    }

    public static function getTypes()
    {
        return [
            'photo' => 'Photo',
            'video' => 'Vidéo'
        ];
    }
}
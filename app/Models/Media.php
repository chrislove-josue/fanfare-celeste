<?php
// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'album_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'alt_text',
        'order',
        'views_count',
        'likes_count',
        'is_cover',
        'is_published',
        'metadata',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'is_published' => 'boolean',
        'order' => 'integer',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'metadata' => 'array',
    ];

    protected $appends = ['file_url', 'formatted_size', 'type_icon'];

    // Relations
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    // Accesseurs
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return '0 bytes';
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    public function getTypeIconAttribute(): string
    {
        return $this->type === 'photo' ? 'fa-image' : 'fa-video';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopePhotos($query)
    {
        return $query->where('type', 'photo');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Méthodes
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }
}
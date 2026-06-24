<?php
// app/Models/GalleryMedia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GalleryMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gallery_media';

    protected $fillable = [
        'gallery_id',
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

    // ============================================
    // RELATIONS
    // ============================================
    
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    // ============================================
    // ACCESSORS
    // ============================================
    
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return '0 bytes';
        
        $units = ['bytes', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return number_format($bytes / pow(1024, $factor), 2) . ' ' . $units[$factor];
    }

    public function getTypeIconAttribute(): string
    {
        return $this->type === 'photo' ? 'fa-image' : 'fa-video';
    }

    public function getIsNewAttribute(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }

    // ============================================
    // SCOPES
    // ============================================
    
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

    public function scopeCover($query)
    {
        return $query->where('is_cover', true);
    }

    // ============================================
    // MÉTHODES
    // ============================================
    
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    public function togglePublish()
    {
        $this->is_published = !$this->is_published;
        $this->save();
        return $this;
    }

    public function setAsCover()
    {
        // Retirer le statut cover des autres médias de la galerie
        $this->gallery->media()->update(['is_cover' => false]);
        
        // Définir celui-ci comme cover
        $this->is_cover = true;
        $this->save();
        
        return $this;
    }
}
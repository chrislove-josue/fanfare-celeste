<?php
// app/Models/Gallery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'category',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'views_count',
        'likes_count',
        'downloads_count',
        'is_published',
        'published_at',
        'order',
        'member_id',
        'alt_text',
        'metadata',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'downloads_count' => 'integer',
        'order' => 'integer',
        'metadata' => 'array',
    ];

    protected $appends = [
        'file_url',
        'category_label',
        'type_icon',
        'formatted_size',
        'is_new',
    ];

    // Relations
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Accesseurs
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        return Storage::url($this->file_path);
    }

    public function getCategoryLabelAttribute(): string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $this->category;
    }

    public function getTypeIconAttribute(): string
    {
        return $this->type === 'photo' ? 'fa-image' : 'fa-video';
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

    public function getIsNewAttribute(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }

    // Méthodes statiques
    public static function getCategories()
    {
        return [
            'concerts' => '🎵 Concerts',
            'rehearsals' => '🎶 Répétitions',
            'trophies' => '🏆 Trophées et distinctions',
            'events' => '📅 Événements',
            'members' => '👥 Vie des membres',
            'ceremonies' => '⛪ Cérémonies',
            'group' => '📸 Photos de groupe',
        ];
    }

    public static function getTypes()
    {
        return [
            'photo' => 'Photo',
            'video' => 'Vidéo',
        ];
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where(function($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeMostViewed($query, $limit = 6)
    {
        return $query->orderBy('views_count', 'desc')->take($limit);
    }

    public function scopeRecent($query, $limit = 6)
    {
        return $query->orderBy('created_at', 'desc')->take($limit);
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

    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    public function togglePublish()
    {
        $this->is_published = !$this->is_published;
        if ($this->is_published && !$this->published_at) {
            $this->published_at = now();
        }
        $this->save();
        
        return $this;
    }
}
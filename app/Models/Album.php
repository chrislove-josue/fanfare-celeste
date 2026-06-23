<?php
// app/Models/Album.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'category',
        'is_published',
        'event_date',
        'location',
        'order',
        'views_count',
        'likes_count',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'event_date' => 'date',
        'order' => 'integer',
        'views_count' => 'integer',
        'likes_count' => 'integer',
    ];

    protected $appends = ['cover_image_url', 'media_count', 'category_label'];

    // Relations
    public function media()
    {
        return $this->hasMany(Media::class)->orderBy('order');
    }

    public function publishedMedia()
    {
        return $this->hasMany(Media::class)->where('is_published', true)->orderBy('order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accesseurs
    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        
        // Si pas de cover, prendre la première photo de l'album
        $firstMedia = $this->media()->where('type', 'photo')->first();
        if ($firstMedia) {
            return Storage::url($firstMedia->file_path);
        }
        
        return null;
    }

    public function getMediaCountAttribute(): int
    {
        return $this->media()->count();
    }

    public function getCategoryLabelAttribute(): string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $this->category;
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

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
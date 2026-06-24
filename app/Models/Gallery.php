<?php
// app/Models/Gallery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gallery extends Model
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

    // ============================================
    // RELATIONS
    // ============================================
    
    public function media()
    {
        return $this->hasMany(GalleryMedia::class)->orderBy('order');
    }

    public function publishedMedia()
    {
        return $this->hasMany(GalleryMedia::class)->where('is_published', true)->orderBy('order');
    }

    public function photos()
    {
        return $this->hasMany(GalleryMedia::class)->where('type', 'photo')->orderBy('order');
    }

    public function videos()
    {
        return $this->hasMany(GalleryMedia::class)->where('type', 'video')->orderBy('order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============================================
    // ACCESSORS
    // ============================================
    
    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        
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

    // ============================================
    // MÉTHODES STATIQUES
    // ============================================
    
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

    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // ============================================
    // SCOPES
    // ============================================
    
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

    public function scopeMostViewed($query, $limit = 6)
    {
        return $query->orderBy('views_count', 'desc')->take($limit);
    }

    public function scopeRecent($query, $limit = 6)
    {
        return $query->orderBy('created_at', 'desc')->take($limit);
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function togglePublish()
    {
        $this->is_published = !$this->is_published;
        $this->save();
        return $this;
    }
}
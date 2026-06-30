<?php
// app/Models/News.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'news';
    
    protected $fillable = [
        'title', 'slug', 'content', 'cover_image_path',
        'status', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    // Accesseur pour l'extrait (utilise le contenu)
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }
    
    // Accesseur pour l'image (pour compatibilité avec les vues)
    public function getFeaturedImageAttribute()
    {
        return $this->cover_image_path;
    }
}
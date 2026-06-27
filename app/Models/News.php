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
        'title', 'slug', 'content', 'excerpt', 'category',
        'featured_image', 'status', 'published_at', 'views_count',
        'author_id', 'meta_title', 'meta_description', 'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'is_featured' => 'boolean'
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getExcerptAttribute($value)
    {
        return $value ?? Str::limit(strip_tags($this->content), 150);
    }
}
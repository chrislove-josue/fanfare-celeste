<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicNewsController extends Controller
{
    public function index()
    {
        $news = News::query()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('news.public.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Incrémenter le compteur de vues
        if (isset($news->views_count)) {
            $news->increment('views_count');
        }

        $relatedNews = News::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('news.public.show', compact('news', 'relatedNews'));
    }
}
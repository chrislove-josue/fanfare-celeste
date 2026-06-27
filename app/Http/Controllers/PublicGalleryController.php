<?php
// app/Http/Controllers/PublicGalleryController.php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryMedia;
use Illuminate\Http\Request;

class PublicGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::withCount('media')->published()->ordered();

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $galleries = $query->paginate(9);
        $categories = Gallery::getCategories();

        // Statistiques
        $totalGalleries = Gallery::published()->count();
        $totalMedia = GalleryMedia::where('is_published', true)->count();
        $totalPhotos = GalleryMedia::where('is_published', true)->where('type', 'photo')->count();
        $totalVideos = GalleryMedia::where('is_published', true)->where('type', 'video')->count();

        // Galeries en vedette
        $featuredGalleries = Gallery::published()
            ->withCount('media')
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get();

        // Dernières galeries
        $recentGalleries = Gallery::published()
            ->withCount('media')
            ->latest()
            ->take(6)
            ->get();

        // ========== 🎬 RÉCUPÉRATION DES VIDÉOS YOUTUBE ==========
        $videoController = new VideoController();
        $videos = $videoController->getVideosList();

        return view('public.gallery.index', compact(
            'galleries',
            'categories',
            'totalGalleries',
            'totalMedia',
            'totalPhotos',
            'totalVideos',
            'featuredGalleries',
            'recentGalleries',
            'videos' // ✅ Passage des vidéos à la vue
        ));
    }

    public function show(Gallery $gallery)
    {
        // Incrémenter les vues
        $gallery->incrementViews();

        $media = $gallery->publishedMedia()->get();
        $related = Gallery::published()
            ->where('category', $gallery->category)
            ->where('id', '!=', $gallery->id)
            ->take(4)
            ->get();

        return view('public.gallery.show', compact('gallery', 'media', 'related'));
    }

    public function byCategory($category)
    {
        $categories = Gallery::getCategories();
        
        if (!isset($categories[$category])) {
            abort(404);
        }

        $galleries = Gallery::published()
            ->byCategory($category)
            ->ordered()
            ->paginate(12);

        $categoryLabel = $categories[$category];

        return view('public.gallery.category', compact('galleries', 'categoryLabel', 'category'));
    }

    public function like(Gallery $gallery)
    {
        $gallery->incrementLikes();
        
        return back()->with('success', 'Merci pour votre like !');
    }
}
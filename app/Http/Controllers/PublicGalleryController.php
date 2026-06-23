<?php
// app/Http/Controllers/PublicGalleryController.php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class PublicGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::published()->ordered();

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $galleries = $query->paginate(12);
        $categories = Gallery::getCategories();
        
        // Statistiques
        $totalPhotos = Gallery::published()->where('type', 'photo')->count();
        $totalVideos = Gallery::published()->where('type', 'video')->count();
        $totalGalleries = Gallery::published()->count();

        // Dernières photos par catégorie
        $latestByCategory = [];
        foreach (array_keys($categories) as $category) {
            $latestByCategory[$category] = Gallery::published()
                ->byCategory($category)
                ->latest()
                ->take(3)
                ->get();
        }

        // Mise en avant (les plus vus)
        $mostViewed = Gallery::published()
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();

        return view('public.gallery.index', compact(
            'galleries',
            'categories',
            'totalPhotos',
            'totalVideos',
            'totalGalleries',
            'latestByCategory',
            'mostViewed'
        ));
    }

    public function show(Gallery $gallery)
    {
        // Incrémenter le compteur de vues
        $gallery->incrementViews();

        $related = Gallery::published()
            ->where('category', $gallery->category)
            ->where('id', '!=', $gallery->id)
            ->take(4)
            ->get();

        return view('public.gallery.show', compact('gallery', 'related'));
    }

    public function byCategory($category)
    {
        $categories = Gallery::getCategories();
        
        // Vérifier si la catégorie existe
        if (!isset($categories[$category])) {
            abort(404, 'Catégorie non trouvée');
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
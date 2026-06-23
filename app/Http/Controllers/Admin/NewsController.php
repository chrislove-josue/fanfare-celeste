<?php
// app/Http/Controllers/Admin/NewsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();
        $eventQuery = Event::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            $eventQuery->where('title', 'LIKE', "%{$search}%")
                       ->orWhere('description', 'LIKE', "%{$search}%");
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'published' || $status === 'draft') {
                $query->where('status', $status);
            } else {
                $eventQuery->where('status', $status);
            }
        }

        // Récupérer les actualités et événements
        $news = $query->latest()->get()->map(function($item) {
            $item->type = 'news';
            return $item;
        });

        $events = $eventQuery->latest()->get()->map(function($item) {
            $item->type = 'event';
            $item->excerpt = $item->description;
            return $item;
        });

        // Fusionner et trier par date
        $items = $news->concat($events)->sortByDesc(function($item) {
            return $item->published_at ?? $item->start_date ?? $item->created_at;
        });

        // Filtre par type
        if ($request->filled('type') && $request->type === 'news') {
            $items = $items->filter(function($item) {
                return $item->type === 'news';
            });
        } elseif ($request->filled('type') && $request->type === 'event') {
            $items = $items->filter(function($item) {
                return $item->type === 'event';
            });
        }

        // Statistiques
        $totalNews = News::count();
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('status', 'upcoming')->count();
        $publishedCount = News::where('status', 'published')->count() + Event::where('status', 'upcoming')->count();

        // Pagination manuelle (si nécessaire)
        $items = $items->values();

        return view('admin.news.index', compact(
            'items', 
            'totalNews', 
            'totalEvents', 
            'upcomingEvents', 
            'publishedCount'
        ));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Vérifier l'unicité du slug
        if (News::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $validated['slug'] . '-' . time();
        }
        
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('news', 'public');
            $validated['featured_image'] = $path;
        }

        if ($validated['status'] === 'published' && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité créée avec succès !');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Vérifier l'unicité du slug
        if (News::where('slug', $validated['slug'])->where('id', '!=', $news->id)->exists()) {
            $validated['slug'] = $validated['slug'] . '-' . time();
        }

        if ($request->hasFile('featured_image')) {
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }
            $path = $request->file('featured_image')->store('news', 'public');
            $validated['featured_image'] = $path;
        }

        if ($validated['status'] === 'published' && !$news->published_at) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité mise à jour avec succès !');
    }

    public function destroy(News $news)
    {
        if ($news->featured_image) {
            Storage::disk('public')->delete($news->featured_image);
        }
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité supprimée avec succès !');
    }
}
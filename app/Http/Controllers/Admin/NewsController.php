<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct()
    {
        // Permissions pour les actualités
        $this->middleware('permission:manage news')->only([
            'index', 'show', 'create', 'store', 'edit', 'update', 'destroy'
        ]);
        
        // Permissions pour les événements (gérés via l'interface unifiée)
        $this->middleware('permission:view events')->only([
            'showEvent', 'index'
        ]);
        $this->middleware('permission:edit events')->only([
            'editEvent', 'updateEvent'
        ]);
        $this->middleware('permission:delete events')->only([
            'destroyEvent'
        ]);
        $this->middleware('permission:manage participations')->only([
            'addParticipant', 'updateParticipation', 'removeParticipant'
        ]);
    }

    /**
     * Display a listing of news and events.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('manage news') && !auth()->user()->can('view events')) {
            abort(403, 'Accès non autorisé');
        }

        $query = News::query();
        $eventQuery = Event::query();

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
            $eventQuery->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if (in_array($status, ['published', 'draft'])) {
                $query->where('status', $status);
            } else {
                $eventQuery->where('status', $status);
            }
        }

        // Récupérer les actualités et événements
        $news = $query->latest()->get()->map(function($item) {
            $item->type = 'news';
            $item->display_date = $item->published_at ?? $item->created_at;
            // Pour compatibilité avec les vues
            $item->featured_image = $item->cover_image_path;
            return $item;
        });

        $events = $eventQuery->latest()->get()->map(function($item) {
            $item->type = 'event';
            $item->excerpt = $item->description;
            $item->display_date = $item->start_date ?? $item->created_at;
            return $item;
        });

        // Fusionner et trier par date
        $items = $news->concat($events)->sortByDesc(function($item) {
            return $item->display_date;
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
        $publishedCount = News::where('status', 'published')->count() + Event::whereIn('status', ['upcoming', 'ongoing'])->count();

        $items = $items->values();

        return view('admin.news.index', compact(
            'items', 
            'totalNews', 
            'totalEvents', 
            'upcomingEvents', 
            'publishedCount'
        ));
    }

    /**
     * Show the form for creating a new news.
     */
    public function create()
    {
        if (!auth()->user()->can('manage news')) {
            abort(403, 'Accès non autorisé');
        }
        return view('admin.news.create');
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('manage news')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        if (News::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $validated['slug'] . '-' . time();
        }
        
        // Utiliser cover_image_path au lieu de featured_image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('news', 'public');
            $validated['cover_image_path'] = $path;
        }
        
        // Supprimer featured_image car ce n'est pas une colonne de la table
        unset($validated['featured_image']);

        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité créée avec succès !');
    }

    /**
     * Display the specified news.
     */
    public function show(News $news)
    {
        if (!auth()->user()->can('manage news')) {
            abort(403, 'Accès non autorisé');
        }
        // Ajouter featured_image pour compatibilité
        $news->featured_image = $news->cover_image_path;
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(News $news)
    {
        if (!auth()->user()->can('manage news')) {
            abort(403, 'Accès non autorisé');
        }
        $news->featured_image = $news->cover_image_path;
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, News $news)
    {
        if (!auth()->user()->can('manage news')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        if (News::where('slug', $validated['slug'])->where('id', '!=', $news->id)->exists()) {
            $validated['slug'] = $validated['slug'] . '-' . time();
        }

        // Utiliser cover_image_path au lieu de featured_image
        if ($request->hasFile('featured_image')) {
            if ($news->cover_image_path) {
                Storage::disk('public')->delete($news->cover_image_path);
            }
            $path = $request->file('featured_image')->store('news', 'public');
            $validated['cover_image_path'] = $path;
        }
        
        // Supprimer featured_image car ce n'est pas une colonne de la table
        unset($validated['featured_image']);

        if ($validated['status'] === 'published' && !$news->published_at) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité mise à jour avec succès !');
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(News $news)
    {
        if (!auth()->user()->can('manage news')) {
            abort(403, 'Accès non autorisé');
        }

        if ($news->cover_image_path) {
            Storage::disk('public')->delete($news->cover_image_path);
        }
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Actualité supprimée avec succès !');
    }

    // ============================================
    // GESTION DES ÉVÉNEMENTS (Interface unifiée)
    // ============================================

    /**
     * Display the specified event.
     */
    public function showEvent(Event $event)
    {
        if (!auth()->user()->can('view events')) {
            abort(403, 'Accès non autorisé');
        }

        $participants = $event->participations()->with('member')->get();
        $availableMembers = Member::where('status', 'active')->get();

        $membersTotal = Member::count();
        $publicRegistrationsCount = \App\Models\EventPublicRegistration::where('event_id', $event->id)->count();

        $totalParticipants = $membersTotal + $publicRegistrationsCount;

        $presentCount = $participants->where('attendance_status', 'present')->count();
        $absentCount = $participants->where('attendance_status', 'absent')->count();
        $lateCount = $participants->where('attendance_status', 'late')->count();

        return view('admin.news.show-event', compact(
            'event', 
            'participants', 
            'availableMembers', 
            'totalParticipants', 
            'presentCount', 
            'absentCount', 
            'lateCount'
        ));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function editEvent(Event $event)
    {
        if (!auth()->user()->can('edit events')) {
            abort(403, 'Accès non autorisé');
        }

        $users = \App\Models\User::all();
        $types = Event::getTypes();
        $statuses = Event::getStatuses();
        
        return view('admin.news.edit-event', compact('event', 'users', 'types', 'statuses'));
    }

    /**
     * Update the specified event in storage.
     */
    public function updateEvent(Request $request, Event $event)
    {
        if (!auth()->user()->can('edit events')) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string',
            'location_url' => 'nullable|url',
            'responsible_id' => 'nullable|exists:users,id',
            'status' => 'required|in:planned,upcoming,ongoing,completed,cancelled',
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $validated['poster_path'] = $request->file('poster')->store('events/posters', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.news.show-event', $event)
            ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroyEvent(Event $event)
    {
        if (!auth()->user()->can('delete events')) {
            abort(403, 'Accès non autorisé');
        }

        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }
        
        $event->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Événement supprimé avec succès !');
    }

    /**
     * Add a participant to an event.
     */
    public function addParticipant(Request $request, Event $event)
    {
        if (!auth()->user()->can('manage participations')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $existing = EventParticipation::where('event_id', $event->id)
            ->where('member_id', $request->member_id)
            ->first();

        if ($existing) {
            return back()->with('warning', 'Ce membre est déjà inscrit à cet événement');
        }

        EventParticipation::create([
            'event_id' => $event->id,
            'member_id' => $request->member_id,
            'attendance_status' => 'confirmed'
        ]);

        return redirect()->route('admin.news.show-event', $event)
            ->with('success', 'Participant ajouté avec succès');
    }

    /**
     * Update participation status.
     */
    public function updateParticipation(Request $request, Event $event, EventParticipation $participation)
    {
        if (!auth()->user()->can('manage participations')) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'attendance_status' => 'required|in:confirmed,present,absent,late,excused',
            'notes' => 'nullable|string'
        ]);

        $participation->update([
            'attendance_status' => $request->attendance_status,
            'notes' => $request->notes,
            'arrival_time' => $request->attendance_status == 'present' ? now() : $participation->arrival_time
        ]);

        return redirect()->route('admin.news.show-event', $event)
            ->with('success', 'Statut de participation mis à jour');
    }

    /**
     * Remove a participant from an event.
     */
    public function removeParticipant(Event $event, EventParticipation $participation)
    {
        if (!auth()->user()->can('manage participations')) {
            abort(403, 'Accès non autorisé');
        }

        $participation->delete();

        return redirect()->route('admin.news.show-event', $event)
            ->with('success', 'Participant retiré avec succès');
    }
}
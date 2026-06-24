<?php
// app/Http/Controllers/Admin/GalleryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage gallery')->except(['index', 'show']);
    }

    /**
     * Liste des galeries
     */
    public function index(Request $request)
    {
        $query = Gallery::withCount('media');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === 'yes');
        }

        $galleries = $query->latest()->paginate(12);
        $categories = Gallery::getCategories();

        // Statistiques
        $totalGalleries = Gallery::count();
        $publishedGalleries = Gallery::where('is_published', true)->count();
        $draftGalleries = Gallery::where('is_published', false)->count();
        $totalMedia = GalleryMedia::count();
        $totalPhotos = GalleryMedia::where('type', 'photo')->count();
        $totalVideos = GalleryMedia::where('type', 'video')->count();

        return view('admin.gallery.index', compact(
            'galleries',
            'categories',
            'totalGalleries',
            'publishedGalleries',
            'draftGalleries',
            'totalMedia',
            'totalPhotos',
            'totalVideos'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Gallery::getCategories();
        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle galerie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Gallery::generateSlug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['created_by'] = auth()->id();
        $validated['order'] = $request->order ?? 0;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('galleries/covers', 'public');
            $validated['cover_image'] = $path;
        }

        $gallery = Gallery::create($validated);

        return redirect()->route('admin.gallery.show', $gallery)
            ->with('success', 'Galerie créée avec succès !');
    }

    /**
     * Afficher une galerie
     */
    public function show(Gallery $gallery)
    {
        $media = $gallery->media()->orderBy('order')->get();
        return view('admin.gallery.show', compact('gallery', 'media'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Gallery $gallery)
    {
        $categories = Gallery::getCategories();
        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    /**
     * Mettre à jour une galerie
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['order'] = $request->order ?? 0;

        if ($request->hasFile('cover_image')) {
            if ($gallery->cover_image) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $path = $request->file('cover_image')->store('galleries/covers', 'public');
            $validated['cover_image'] = $path;
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.show', $gallery)
            ->with('success', 'Galerie mise à jour avec succès !');
    }

    /**
     * Supprimer une galerie
     */
    public function destroy(Gallery $gallery)
    {
        // Supprimer tous les médias
        foreach ($gallery->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        // Supprimer l'image de couverture
        if ($gallery->cover_image) {
            Storage::disk('public')->delete($gallery->cover_image);
        }
        
        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Galerie supprimée avec succès !');
    }

    /**
     * Ajouter un média à une galerie
     */
    public function addMedia(Request $request, Gallery $gallery)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'is_cover' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('galleries/' . $gallery->id . '/media', $fileName, 'public');

        $isPhoto = in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
        
        $media = GalleryMedia::create([
            'gallery_id' => $gallery->id,
            'title' => $request->title ?? $file->getClientOriginalName(),
            'description' => $request->description,
            'type' => $isPhoto ? 'photo' : 'video',
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'alt_text' => $request->alt_text,
            'is_cover' => $request->has('is_cover'),
            'is_published' => $request->has('is_published'),
            'order' => $gallery->media()->count() + 1,
        ]);

        // Si c'est la première photo et que la galerie n'a pas de cover
        if (!$gallery->cover_image && $isPhoto) {
            $gallery->update(['cover_image' => $filePath]);
        }

        return redirect()->route('admin.gallery.show', $gallery)
            ->with('success', 'Média ajouté avec succès !');
    }

    /**
     * Supprimer un média
     */
    public function deleteMedia(Gallery $gallery, GalleryMedia $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return redirect()->route('admin.gallery.show', $gallery)
            ->with('success', 'Média supprimé avec succès !');
    }

    /**
     * Upload en masse
     */
    public function uploadMass(Request $request, Gallery $gallery)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240',
        ]);

        $uploaded = 0;
        $failed = 0;

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('galleries/' . $gallery->id . '/media', $fileName, 'public');

                $isPhoto = in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

                GalleryMedia::create([
                    'gallery_id' => $gallery->id,
                    'title' => $file->getClientOriginalName(),
                    'type' => $isPhoto ? 'photo' : 'video',
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_published' => true,
                    'order' => $gallery->media()->count() + $uploaded + 1,
                ]);
                $uploaded++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return redirect()->route('admin.gallery.show', $gallery)
            ->with('success', "{$uploaded} fichier(s) uploadé(s) avec succès. {$failed} échec(s).");
    }

    /**
     * Basculer la publication
     */
    public function togglePublish(Gallery $gallery)
    {
        $gallery->is_published = !$gallery->is_published;
        $gallery->save();

        $status = $gallery->is_published ? 'publiée' : 'dépubliée';
        return redirect()->route('admin.gallery.index')
            ->with('success', "Galerie {$status} avec succès !");
    }

    /**
     * Réordonner les médias
     */
    public function reorderMedia(Request $request, Gallery $gallery)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:gallery_media,id',
        ]);

        foreach ($request->order as $index => $mediaId) {
            GalleryMedia::where('id', $mediaId)
                ->where('gallery_id', $gallery->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
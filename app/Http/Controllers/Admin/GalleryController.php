<?php
// app/Http/Controllers/Admin/GalleryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Media;
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
     * Liste des albums
     */
    public function index(Request $request)
    {
        $query = Album::withCount('media');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === 'yes');
        }

        $albums = $query->latest()->paginate(15);
        $categories = Album::getCategories();

        return view('admin.gallery.index', compact('albums', 'categories'));
    }

    /**
     * Formulaire de création d'album
     */
    public function create()
    {
        $categories = Album::getCategories();
        return view('admin.gallery.create', compact('categories'));
    }

    /**
     * Enregistrer un album
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

        $validated['slug'] = Album::generateSlug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['created_by'] = auth()->id();
        $validated['order'] = $request->order ?? 0;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('albums/covers', 'public');
            $validated['cover_image'] = $path;
        }

        $album = Album::create($validated);

        return redirect()->route('admin.gallery.show', $album)
            ->with('success', 'Album créé avec succès !');
    }

    /**
     * Afficher un album
     */
    public function show(Album $album)
    {
        $media = $album->media()->orderBy('order')->get();
        return view('admin.gallery.show', compact('album', 'media'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Album $album)
    {
        $categories = Album::getCategories();
        return view('admin.gallery.edit', compact('album', 'categories'));
    }

    /**
     * Mettre à jour un album
     */
    public function update(Request $request, Album $album)
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
            if ($album->cover_image) {
                Storage::disk('public')->delete($album->cover_image);
            }
            $path = $request->file('cover_image')->store('albums/covers', 'public');
            $validated['cover_image'] = $path;
        }

        $album->update($validated);

        return redirect()->route('admin.gallery.show', $album)
            ->with('success', 'Album mis à jour avec succès !');
    }

    /**
     * Supprimer un album
     */
    public function destroy(Album $album)
    {
        // Supprimer tous les médias associés
        foreach ($album->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        if ($album->cover_image) {
            Storage::disk('public')->delete($album->cover_image);
        }
        
        $album->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Album supprimé avec succès !');
    }

    /**
     * Ajouter un média à un album
     */
    public function addMedia(Request $request, Album $album)
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
        $filePath = $file->storeAs('albums/' . $album->id . '/media', $fileName, 'public');

        $isPhoto = in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
        
        $media = Media::create([
            'album_id' => $album->id,
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
            'order' => $album->media()->count() + 1,
        ]);

        // Si c'est la première photo et que l'album n'a pas de cover
        if (!$album->cover_image && $isPhoto) {
            $album->update(['cover_image' => $filePath]);
        }

        return redirect()->route('admin.gallery.show', $album)
            ->with('success', 'Média ajouté avec succès !');
    }

    /**
     * Supprimer un média
     */
    public function deleteMedia(Album $album, Media $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return redirect()->route('admin.gallery.show', $album)
            ->with('success', 'Média supprimé avec succès !');
    }

    /**
     * Mettre à jour l'ordre des médias
     */
    public function reorderMedia(Request $request, Album $album)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:media,id',
        ]);

        foreach ($request->order as $index => $mediaId) {
            Media::where('id', $mediaId)->where('album_id', $album->id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Upload en masse
     */
    public function uploadMass(Request $request, Album $album)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240',
        ]);

        $uploaded = 0;
        $failed = 0;

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('albums/' . $album->id . '/media', $fileName, 'public');

                $isPhoto = in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

                Media::create([
                    'album_id' => $album->id,
                    'title' => $file->getClientOriginalName(),
                    'type' => $isPhoto ? 'photo' : 'video',
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_published' => true,
                    'order' => $album->media()->count() + $uploaded + 1,
                ]);
                $uploaded++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return redirect()->route('admin.gallery.show', $album)
            ->with('success', "{$uploaded} fichier(s) uploadé(s) avec succès. {$failed} échec(s).");
    }
}
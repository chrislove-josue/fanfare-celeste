<?php
// app/Http/Controllers/Admin/GalleryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage gallery')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Liste des photos de la galerie
     */
    public function index(Request $request)
    {
        $query = Gallery::with('member');

        // Filtres
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

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === 'yes');
        }

        $galleries = $query->latest()->paginate(15);
        $categories = Gallery::getCategories();
        $types = Gallery::getTypes();

        // Statistiques
        $totalPhotos = Gallery::count();
        $publishedPhotos = Gallery::where('is_published', true)->count();
        $draftPhotos = Gallery::where('is_published', false)->count();
        $totalViews = Gallery::sum('views_count');
        $totalLikes = Gallery::sum('likes_count');

        return view('admin.gallery.index', compact(
            'galleries',
            'categories',
            'types',
            'totalPhotos',
            'publishedPhotos',
            'draftPhotos',
            'totalViews',
            'totalLikes'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Gallery::getCategories();
        $types = Gallery::getTypes();
        return view('admin.gallery.create', compact('categories', 'types'));
    }

    /**
     * Enregistrer une nouvelle photo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Gallery::getCategories())),
            'type' => 'required|string|in:photo,video',
            'file' => 'required|file|max:10240', // 10MB max
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Gérer le fichier
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('galleries', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['order'] = $request->order ?? 0;
        $validated['views_count'] = 0;
        $validated['likes_count'] = 0;

        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Photo ajoutée avec succès !');
    }

    /**
     * Afficher les détails d'une photo
     */
    public function show(Gallery $gallery)
    {
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Gallery $gallery)
    {
        $categories = Gallery::getCategories();
        $types = Gallery::getTypes();
        return view('admin.gallery.edit', compact('gallery', 'categories', 'types'));
    }

    /**
     * Mettre à jour une photo
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Gallery::getCategories())),
            'type' => 'required|string|in:photo,video',
            'file' => 'nullable|file|max:10240',
            'is_published' => 'boolean',
            'order' => 'nullable|integer',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Gérer le nouveau fichier si présent
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($gallery->file_path) {
                Storage::disk('public')->delete($gallery->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('galleries', $fileName, 'public');

            $validated['file_path'] = $filePath;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['order'] = $request->order ?? 0;

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Photo mise à jour avec succès !');
    }

    /**
     * Supprimer une photo
     */
    public function destroy(Gallery $gallery)
    {
        // Supprimer le fichier
        if ($gallery->file_path) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Photo supprimée avec succès !');
    }

    /**
     * Basculer la publication
     */
    public function togglePublish(Gallery $gallery)
    {
        $gallery->togglePublish();

        $status = $gallery->is_published ? 'publiée' : 'dépubliée';
        return redirect()->route('admin.gallery.index')
            ->with('success', "Photo {$status} avec succès !");
    }

    /**
     * Upload en masse
     */
    public function uploadMass(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240|mimes:jpeg,png,jpg,gif,webp,mp4',
            'category' => 'required|string',
        ]);

        $uploaded = 0;
        $failed = 0;

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('galleries', $fileName, 'public');

                Gallery::create([
                    'title' => $file->getClientOriginalName(),
                    'category' => $request->category,
                    'type' => in_array($file->getMimeType(), ['video/mp4', 'video/webm']) ? 'video' : 'photo',
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_published' => true,
                    'order' => 0,
                ]);
                $uploaded++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return redirect()->route('admin.gallery.index')
            ->with('success', "{$uploaded} fichier(s) uploadé(s) avec succès. {$failed} échec(s).");
    }
}
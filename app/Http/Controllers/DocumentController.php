<?php
// app/Http/Controllers/DocumentController.php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view documents')->only(['index', 'show', 'download']);
        $this->middleware('permission:upload documents')->only(['create', 'store']);
        $this->middleware('permission:delete documents')->only(['destroy']);
    }

    /**
     * Affiche la liste des documents
     */
    public function index(Request $request)
    {
        $query = Document::with('uploader');
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public === 'yes');
        }
        
        if ($request->filled('uploaded_by')) {
            $query->where('uploaded_by', $request->uploaded_by);
        }

        $documents = $query->latest()->paginate(20);
        $categories = Document::getCategories();
        $users = User::orderBy('name')->get();

        // Statistiques
        $totalDocuments = Document::count();
        $publicDocuments = Document::where('is_public', true)->count();
        $privateDocuments = Document::where('is_public', false)->count();
        $pendingDocuments = Document::where('status', 'pending')->count();
        $approvedDocuments = Document::where('status', 'approved')->count();
        $rejectedDocuments = Document::where('status', 'rejected')->count();

        // Documents par catégorie
        $categoryStats = [];
        foreach ($categories as $key => $label) {
            $categoryStats[$key] = Document::where('category', $key)->count();
        }

        return view('admin.documents.index', compact(
            'documents',
            'categories',
            'users',
            'totalDocuments',
            'publicDocuments',
            'privateDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'rejectedDocuments',
            'categoryStats'
        ));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $categories = Document::getCategories();
        return view('admin.documents.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'document_date' => 'nullable|date',
            'is_public' => 'boolean',
            'file' => 'required|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents', 'public');
            
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getMimeType();
        }

        $validated['uploaded_by'] = auth()->id();
        $validated['is_public'] = $request->has('is_public');

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document ajouté avec succès');
    }

    /**
     * Affiche les détails d'un document
     */
    public function show(Document $document)
    {
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Télécharge un document
     */
    public function download(Document $document)
    {
        if (!$document->is_public && !auth()->user()->hasPermissionTo('view documents')) {
            abort(403);
        }

        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Le fichier n\'existe pas.');
        }

        return response()->download($filePath, $document->file_name);
    }

    /**
     * Supprime un document
     */
    public function destroy(Document $document)
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document supprimé avec succès');
    }
}
<?php
// app/Http/Controllers/Admin/DocumentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view documents')->only(['index', 'show']);
        $this->middleware('permission:upload documents')->only(['create', 'store']);
        $this->middleware('permission:delete documents')->only(['destroy']);
    }

    /**
     * Liste de tous les documents
     */
    public function index(Request $request)
    {
        $query = Document::with('uploader');
        
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
        
        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public === 'yes');
        }
        
        if ($request->filled('uploaded_by')) {
            $query->where('uploaded_by', $request->uploaded_by);
        }
        
        $documents = $query->latest()->paginate(20);
        
        // Données pour les filtres
        $categories = Document::getCategories();
        $users = User::orderBy('name')->get();
        
        // Statistiques
        $totalDocuments = Document::count();
        $publicDocuments = Document::where('is_public', true)->count();
        $privateDocuments = Document::where('is_public', false)->count();
        
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
            'categoryStats'
        ));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Document::getCategories();
        $users = User::orderBy('name')->get();
        return view('admin.documents.create', compact('categories', 'users'));
    }

    /**
     * Enregistrer un nouveau document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:statutes,minutes,financial_report,activity_program,internal,contract,certificate',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'document_date' => 'nullable|date',
            'is_public' => 'boolean',
            'uploaded_by' => 'required|exists:users,id',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('documents/' . $validated['category'], 'public');

        Document::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'uploaded_by' => $validated['uploaded_by'],
            'document_date' => $validated['document_date'],
            'is_public' => $request->has('is_public'),
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Afficher un document spécifique
     */
    public function show(Document $document)
    {
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Document $document)
    {
        $categories = Document::getCategories();
        $users = User::orderBy('name')->get();
        return view('admin.documents.edit', compact('document', 'categories', 'users'));
    }

    /**
     * Mettre à jour un document
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:statutes,minutes,financial_report,activity_program,internal,contract,certificate',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'document_date' => 'nullable|date',
            'is_public' => 'boolean',
            'uploaded_by' => 'required|exists:users,id',
        ]);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'uploaded_by' => $validated['uploaded_by'],
            'document_date' => $validated['document_date'],
            'is_public' => $request->has('is_public'),
        ];

        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            
            $file = $request->file('file');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->store('documents/' . $validated['category'], 'public');
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getMimeType();
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document mis à jour avec succès.');
    }

    /**
     * Télécharger un document
     */
    public function download(Document $document)
    {
        $filePath = storage_path('app/public/' . $document->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'Le fichier n\'existe pas.');
        }
        
        return response()->download($filePath, $document->file_name);
    }

    /**
     * Supprimer un document
     */
    public function destroy(Document $document)
    {
        // Supprimer le fichier physique
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        
        return redirect()->route('admin.documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Basculer la visibilité d'un document
     */
    public function togglePublic(Document $document)
    {
        $document->update([
            'is_public' => !$document->is_public
        ]);
        
        return redirect()->route('admin.documents.index')
            ->with('success', 'Visibilité du document modifiée.');
    }
}
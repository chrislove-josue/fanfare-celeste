<?php

namespace App\Http\Controllers;

use App\Models\Document;
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
    public function index()
    {
        $documents = Document::with('uploader')->latest()->paginate(20);
        $categories = Document::getCategories();
        
        return view('documents.index', compact('documents', 'categories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $categories = Document::getCategories();
        
        return view('documents.create', compact('categories'));
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
            'file' => 'required|file|max:10240', // 10MB max
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
        return view('documents.show', compact('document'));
    }

    /**
     * Télécharge un document
     */
    public function download(Document $document)
    {
        if (!$document->is_public && !auth()->user()->hasPermissionTo('view documents')) {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Supprime un document
     */
    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document supprimé avec succès');
    }
}
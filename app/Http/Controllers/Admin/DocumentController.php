<?php
// app/Http/Controllers/Admin/DocumentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Member;
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
     * Liste de tous les documents
     */
    public function index(Request $request)
    {
        $query = Document::with(['uploader', 'member']);
        
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
        
        if ($request->filled('submission_type')) {
            $query->where('submission_type', $request->submission_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public === 'yes');
        }
        
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        
        if ($request->filled('uploaded_by')) {
            $query->where('uploaded_by', $request->uploaded_by);
        }
        
        $documents = $query->latest()->paginate(20);
        
        // Données pour les filtres
        $categories = Document::getCategories();
        $submissionTypes = Document::getSubmissionTypes();
        $users = User::orderBy('name')->get();
        $members = Member::orderBy('first_name')->get();
        
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
            'submissionTypes',
            'users',
            'members',
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
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Document::getCategories();
        $submissionTypes = Document::getSubmissionTypes();
        $users = User::orderBy('name')->get();
        $members = Member::orderBy('first_name')->get();
        return view('admin.documents.create', compact('categories', 'submissionTypes', 'users', 'members'));
    }

    /**
     * Enregistrer un nouveau document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240',
            'document_date' => 'nullable|date',
            'is_public' => 'boolean',
            'submission_type' => 'required|in:admin,member,applicant',
            'member_id' => 'nullable|exists:members,id',
            'uploaded_by' => 'required|exists:users,id',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents/' . $validated['category'], 'public');
            
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getMimeType();
        }

        $validated['is_public'] = $request->has('is_public');
        $validated['status'] = $request->status ?? 'pending';

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document ajouté avec succès');
    }

    /**
     * Afficher un document spécifique
     */
    public function show(Document $document)
    {
        $document->load(['uploader', 'member']);
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Télécharger un document
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
     * Supprimer un document
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

    /**
     * Approuver un document
     */
    public function approve(Document $document)
    {
        $document->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        
        return redirect()->route('admin.documents.index')
            ->with('success', 'Document approuvé avec succès.');
    }

    /**
     * Rejeter un document
     */
    public function reject(Request $request, Document $document)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);
        
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        return redirect()->route('admin.documents.index')
            ->with('success', 'Document rejeté.');
    }
}
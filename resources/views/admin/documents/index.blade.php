@extends('layouts.admin')

@section('title', 'Gestion des documents')

@section('page-title', 'Documents')
@section('page-subtitle', 'Gestion des documents de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item active">Documents</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            Gestion des documents
        </h1>
        <div>
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Ajouter un document
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2 text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total documents</div>
                        <div class="h5 mb-0">{{ $documents->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle p-2 text-white">
                            <i class="fas fa-globe"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Publics</div>
                        <div class="h5 mb-0">{{ $documents->where('is_public', true)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-secondary rounded-circle p-2 text-white">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Privés</div>
                        <div class="h5 mb-0">{{ $documents->where('is_public', false)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-info rounded-circle p-2 text-white">
                            <i class="fas fa-folder-open"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Catégories</div>
                        <div class="h5 mb-0">{{ count($categories) }}</div> <!-- Correction ici -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search me-1"></i> Recherche
                    </label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Titre, description...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Visibilité</label>
                    <select name="is_public" class="form-select">
                        <option value="">Tous</option>
                        <option value="yes" {{ request('is_public') == 'yes' ? 'selected' : '' }}>Public</option>
                        <option value="no" {{ request('is_public') == 'no' ? 'selected' : '' }}>Privé</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary" title="Réinitialiser">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Catégories stats -->
    <div class="row g-2 mb-4">
        @foreach($categories as $key => $label)
        <div class="col-md-2">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="h5 mb-0">{{ $documents->where('category', $key)->count() }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Liste des documents -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Uploadé par</th>
                            <th>Fichier</th>
                            <th>Taille</th>
                            <th>Visibilité</th>
                            <th>Date</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                        <tr>
                            <td>{{ $document->id }}</td>
                            <td>
                                <strong>{{ $document->title }}</strong>
                                @if($document->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $categories[$document->category] ?? $document->category }}
                                </span>
                            </td>
                            <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.documents.download', $document) }}" class="text-primary" title="Télécharger">
                                    <i class="fas fa-file-pdf me-1"></i>
                                    {{ Str::limit($document->file_name, 20) }}
                                </a>
                            </td>
                            <td>{{ $document->formatted_size }}</td>
                            <td>
                                @if($document->is_public)
                                    <span class="badge bg-success">
                                        <i class="fas fa-globe me-1"></i> Public
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-lock me-1"></i> Privé
                                    </span>
                                @endif
                            </td>
                            <td>{{ $document->document_date ? $document->document_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Voir -->
                                    <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Télécharger -->
                                    <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-primary" title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    
                                    <!-- Supprimer -->
                                    <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce document ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-file-alt fa-2x d-block mb-2 text-muted"></i>
                                <p class="text-muted">Aucun document trouvé</p>
                                <a href="{{ route('admin.documents.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i> Ajouter un document
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small">
                        Affichage de {{ $documents->firstItem() ?? 0 }} à {{ $documents->lastItem() ?? 0 }} sur {{ $documents->total() }} documents
                    </span>
                </div>
                <div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
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
                        <div class="h5 mb-0">{{ $totalDocuments }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle p-2 text-white">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Approuvés</div>
                        <div class="h5 mb-0">{{ $approvedDocuments }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-warning rounded-circle p-2 text-white">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">En attente</div>
                        <div class="h5 mb-0">{{ $pendingDocuments }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-danger rounded-circle p-2 text-white">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Rejetés</div>
                        <div class="h5 mb-0">{{ $rejectedDocuments }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search me-1"></i> Recherche
                    </label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Titre, description...">
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="submission_type" class="form-select">
                        <option value="">Tous</option>
                        @foreach($submissionTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('submission_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Membre</label>
                    <select name="member_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->first_name }} {{ $member->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
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

    <!-- Liste des documents -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Titre</th>
                            <th>Membre</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Fichier</th>
                            <th>Statut</th>
                            <th>Visibilité</th>
                            <th style="width: 200px;">Actions</th>
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
                                @if($document->member)
                                    <a href="{{ route('admin.members.show', $document->member) }}" class="text-decoration-none">
                                        {{ $document->member->full_name }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $categories[$document->category] ?? $document->category }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $document->submission_type === 'admin' ? 'primary' : ($document->submission_type === 'member' ? 'success' : 'warning') }}">
                                    {{ $submissionTypes[$document->submission_type] ?? $document->submission_type }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.documents.download', $document) }}" class="text-primary" title="Télécharger">
                                    <i class="fas fa-file-pdf me-1"></i>
                                    {{ Str::limit($document->file_name, 20) }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $document->formatted_size }}</small>
                            </td>
                            <td>
                                {!! $document->status_badge !!}
                            </td>
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
                                    
                                    <!-- Approuver (si en attente) -->
                                    @if($document->status === 'pending')
                                        <form action="{{ route('admin.documents.approve', $document) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Approuver" onclick="return confirm('Approuver ce document ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-danger" title="Rejeter" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $document->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    <!-- Supprimer -->
                                    <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce document ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Rejeter -->
                                <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.documents.reject', $document) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-times-circle me-2 text-danger"></i>
                                                        Rejeter le document
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Motif du rejet</label>
                                                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Expliquez pourquoi ce document est rejeté..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times me-2"></i> Rejeter
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
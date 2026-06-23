@extends('layouts.admin')

@section('title', 'Gestion de la galerie')

@section('page-title', 'Galerie')
@section('page-subtitle', 'Gestion des photos et vidéos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Galerie</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-images me-2 text-primary"></i>
            Gestion de la galerie
        </h1>
        <div>
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i> Ajouter une photo
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadMassModal">
                <i class="fas fa-upload me-2"></i> Upload en masse
            </button>
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
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total photos</div>
                        <div class="h5 mb-0">{{ $totalPhotos }}</div>
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
                        <div class="text-muted small">Publiées</div>
                        <div class="h5 mb-0">{{ $publishedPhotos }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-secondary rounded-circle p-2 text-white">
                            <i class="fas fa-eye-slash"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Brouillons</div>
                        <div class="h5 mb-0">{{ $draftPhotos }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-info rounded-circle p-2 text-white">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total likes</div>
                        <div class="h5 mb-0">{{ $totalLikes }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.gallery.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Recherche</label>
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
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="is_published" class="form-select">
                        <option value="">Tous</option>
                        <option value="yes" {{ request('is_published') == 'yes' ? 'selected' : '' }}>Publié</option>
                        <option value="no" {{ request('is_published') == 'no' ? 'selected' : '' }}>Brouillon</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary" title="Réinitialiser">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des photos -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Vues</th>
                            <th>Likes</th>
                            <th>Ordre</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galleries as $gallery)
                        <tr>
                            <td>
                                @if($gallery->file_path)
                                    <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                                         alt="{{ $gallery->title }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted fa-2x"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ Str::limit($gallery->title, 30) }}</strong>
                                @if($gallery->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($gallery->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $gallery->category_label }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-{{ $gallery->type_icon }} me-1"></i>
                                    {{ ucfirst($gallery->type) }}
                                </span>
                            </td>
                            <td>
                                @if($gallery->is_published)
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </td>
                            <td>{{ $gallery->views_count ?? 0 }}</td>
                            <td>{{ $gallery->likes_count ?? 0 }}</td>
                            <td>{{ $gallery->order ?? 0 }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.gallery.toggle-publish', $gallery) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $gallery->is_published ? 'secondary' : 'success' }}" 
                                                title="{{ $gallery->is_published ? 'Dépublier' : 'Publier' }}">
                                            <i class="fas fa-{{ $gallery->is_published ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer" 
                                                onclick="return confirm('Supprimer cette photo ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-images fa-2x d-block mb-2 text-muted"></i>
                                <p class="text-muted">Aucune photo dans la galerie</p>
                                <a href="{{ route('admin.gallery.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i> Ajouter une photo
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
                        Affichage de {{ $galleries->firstItem() ?? 0 }} à {{ $galleries->lastItem() ?? 0 }} sur {{ $galleries->total() }} photos
                    </span>
                </div>
                <div>
                    {{ $galleries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload en masse -->
<div class="modal fade" id="uploadMassModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.upload-mass') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-upload me-2 text-success"></i>
                        Upload en masse
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catégorie</label>
                        <select name="category" class="form-select" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fichiers</label>
                        <input type="file" name="files[]" class="form-control" multiple accept="image/*,video/*" required>
                        <small class="text-muted">Vous pouvez sélectionner plusieurs fichiers (JPG, PNG, GIF, WEBP, MP4)</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Les fichiers seront automatiquement publiés et le titre sera le nom du fichier.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i> Uploader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
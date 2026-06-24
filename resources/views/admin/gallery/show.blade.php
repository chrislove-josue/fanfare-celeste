@extends('layouts.admin')

@section('title', $gallery->title . ' - Galerie')

@section('page-title', $gallery->title)
@section('page-subtitle', 'Gestion des médias de la galerie')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Galerie</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($gallery->title, 30) }}</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-images me-2 text-primary"></i>
                {{ $gallery->title }}
            </h1>
            <p class="text-muted mb-0">{{ $media->count() }} médias dans cette galerie</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
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

    <!-- Informations de la galerie -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Catégorie</strong>
                    <p class="mb-0">{{ $gallery->category_label }}</p>
                </div>
                <div class="col-md-3">
                    <strong>Statut</strong>
                    <p class="mb-0">
                        @if($gallery->is_published)
                            <span class="badge bg-success">Publié</span>
                        @else
                            <span class="badge bg-secondary">Brouillon</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3">
                    <strong>Médias</strong>
                    <p class="mb-0">{{ $media->count() }}</p>
                </div>
                <div class="col-md-3">
                    <strong>Créé le</strong>
                    <p class="mb-0">{{ $gallery->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                @if($gallery->description)
                <div class="col-12">
                    <strong>Description</strong>
                    <p class="mb-0">{{ $gallery->description }}</p>
                </div>
                @endif
                @if($gallery->event_date)
                <div class="col-md-3">
                    <strong>Date de l'événement</strong>
                    <p class="mb-0">{{ $gallery->event_date->format('d/m/Y') }}</p>
                </div>
                @endif
                @if($gallery->location)
                <div class="col-md-3">
                    <strong>Lieu</strong>
                    <p class="mb-0">{{ $gallery->location }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upload de médias -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="card-title mb-0">
                <i class="fas fa-upload me-2 text-primary"></i>
                Ajouter des médias
            </h5>
            <div class="mt-2 mt-md-0">
                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#uploadMassModal">
                    <i class="fas fa-upload me-1"></i> Upload en masse
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                    <i class="fas fa-plus me-1"></i> Ajouter un média
                </button>
            </div>
        </div>
    </div>

    <!-- Liste des médias -->
    <div class="row g-3" id="media-grid">
        @forelse($media as $item)
        <div class="col-md-3 col-lg-2" data-id="{{ $item->id }}">
            <div class="card shadow-sm h-100 hover-shadow transition">
                <div class="position-relative">
                    @if($item->type == 'photo')
                        <img src="{{ $item->file_url }}" 
                             alt="{{ $item->title }}" 
                             class="card-img-top" 
                             style="height: 150px; object-fit: cover;">
                    @else
                        <video src="{{ $item->file_url }}" 
                               class="card-img-top" 
                               style="height: 150px; object-fit: cover;"
                               muted></video>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-3x text-white opacity-75"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-1">
                        <span class="badge bg-dark bg-opacity-75">
                            <i class="fas fa-{{ $item->type_icon }}"></i>
                        </span>
                    </div>
                    @if($item->is_cover)
                        <div class="position-absolute bottom-0 start-0 m-1">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> Une
                            </span>
                        </div>
                    @endif
                    @if(!$item->is_published)
                        <div class="position-absolute bottom-0 start-0 m-1">
                            <span class="badge bg-secondary">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                    @endif
                </div>
                <div class="card-body p-2">
                    <small class="text-muted d-block text-truncate" title="{{ $item->title }}">
                        {{ Str::limit($item->title, 20) }}
                    </small>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">
                            <i class="fas fa-heart text-danger me-1"></i> {{ $item->likes_count ?? 0 }}
                        </small>
                        <div>
                            <form action="{{ route('admin.gallery.delete-media', [$gallery, $item]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer ce média ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-photo-video fa-4x text-muted mb-3"></i>
                <h4>Aucun média dans cette galerie</h4>
                <p class="text-muted">Ajoutez des photos ou vidéos à cette galerie.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMediaModal">
                    <i class="fas fa-plus me-2"></i> Ajouter un média
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Ajouter un média -->
<div class="modal fade" id="addMediaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.add-media', $gallery) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2 text-primary"></i>
                        Ajouter un média
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fichier *</label>
                        <input type="file" name="file" class="form-control" accept="image/*,video/*" required>
                        <small class="text-muted">JPG, PNG, GIF, WEBP, MP4 (max 10MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre</label>
                        <input type="text" name="title" class="form-control" placeholder="Titre du média">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Texte alternatif (SEO)</label>
                        <input type="text" name="alt_text" class="form-control" placeholder="Description SEO">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="is_cover" value="0">
                        <input type="checkbox" class="form-check-input" id="is_cover" name="is_cover" value="1">
                        <label class="form-check-label" for="is_cover">Définir comme couverture</label>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" checked>
                        <label class="form-check-label" for="is_published">Publié</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload en masse -->
<div class="modal fade" id="uploadMassModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.gallery.upload-mass', $gallery) }}" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label fw-semibold">Fichiers</label>
                        <input type="file" name="files[]" class="form-control" multiple accept="image/*,video/*" required>
                        <small class="text-muted">Sélectionnez plusieurs fichiers (JPG, PNG, GIF, WEBP, MP4)</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Les fichiers seront automatiquement publiés avec le nom du fichier comme titre.
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

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
@endpush
@endsection
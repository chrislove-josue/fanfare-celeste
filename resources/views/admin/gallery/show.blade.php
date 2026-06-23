@extends('layouts.admin')

@section('title', $gallery->title . ' - Galerie')

@section('page-title', $gallery->title)
@section('page-subtitle', 'Détails de la photo')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Galerie</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($gallery->title, 30) }}</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-image me-2 text-primary"></i>
            {{ $gallery->title }}
        </h1>
        <div>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
            <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cette photo ?')">
                    <i class="fas fa-trash me-2"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Image principale -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @if($gallery->type == 'photo')
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                             alt="{{ $gallery->title }}" 
                             class="img-fluid w-100" 
                             style="max-height: 600px; object-fit: contain; background: #f8f9fa;">
                    @else
                        <video src="{{ asset('storage/' . $gallery->file_path) }}" 
                               class="img-fluid w-100" 
                               style="max-height: 600px; object-fit: contain; background: #f8f9fa;"
                               controls></video>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">{{ $gallery->title }}</h3>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary me-1">
                            <i class="fas fa-tag me-1"></i> {{ $gallery->category_label }}
                        </span>
                        <span class="badge bg-secondary me-1">
                            <i class="fas fa-{{ $gallery->type_icon }} me-1"></i> {{ ucfirst($gallery->type) }}
                        </span>
                        @if($gallery->is_published)
                            <span class="badge bg-success">
                                <i class="fas fa-eye me-1"></i> Publié
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-eye-slash me-1"></i> Brouillon
                            </span>
                        @endif
                        @if($gallery->category === 'trophies')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-trophy me-1"></i> Distinction
                            </span>
                        @endif
                    </div>

                    @if($gallery->description)
                        <div class="mb-3">
                            <label class="fw-semibold">Description</label>
                            <p class="text-muted mb-0">{{ $gallery->description }}</p>
                        </div>
                    @endif

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Catégorie</span>
                            <span class="fw-semibold">{{ $gallery->category_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Type</span>
                            <span class="fw-semibold">{{ ucfirst($gallery->type) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ordre</span>
                            <span class="fw-semibold">{{ $gallery->order ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Statut</span>
                            <span class="fw-semibold">
                                @if($gallery->is_published)
                                    <span class="badge bg-success">Publié</span>
                                @else
                                    <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </span>
                        </div>
                        @if($gallery->alt_text)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Texte alternatif</span>
                            <span class="fw-semibold">{{ $gallery->alt_text }}</span>
                        </div>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Fichier</span>
                            <span class="fw-semibold">{{ $gallery->file_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Taille</span>
                            <span class="fw-semibold">{{ $gallery->formatted_size }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Type MIME</span>
                            <span class="fw-semibold">{{ $gallery->mime_type }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Créé le</span>
                            <span class="fw-semibold">{{ $gallery->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Modifié le</span>
                            <span class="fw-semibold">{{ $gallery->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Vues</span>
                            <span class="fw-semibold">{{ $gallery->views_count ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Likes</span>
                            <span class="fw-semibold">{{ $gallery->likes_count ?? 0 }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ asset('storage/' . $gallery->file_path) }}" 
                           download="{{ $gallery->file_name }}" 
                           class="btn btn-primary">
                            <i class="fas fa-download me-2"></i> Télécharger le fichier
                        </a>
                        <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Modifier
                        </a>
                        <form action="{{ route('admin.gallery.toggle-publish', $gallery) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-{{ $gallery->is_published ? 'secondary' : 'success' }} w-100">
                                <i class="fas fa-{{ $gallery->is_published ? 'eye-slash' : 'eye' }} me-2"></i>
                                {{ $gallery->is_published ? 'Dépublier' : 'Publier' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Supprimer cette photo ?')">
                                <i class="fas fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Gestion de la galerie')

@section('page-title', 'Galerie')
@section('page-subtitle', 'Gestion des albums et médias')
@section('breadcrumb')
    <li class="breadcrumb-item active">Galerie</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h3 mb-0">
            <i class="fas fa-images me-2 text-primary"></i>
            Gestion de la galerie
        </h1>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Nouvelle galerie
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

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2 text-white">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total galeries</div>
                        <div class="h5 mb-0">{{ $totalGalleries ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle p-2 text-white">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Publiées</div>
                        <div class="h5 mb-0">{{ $publishedGalleries ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-secondary rounded-circle p-2 text-white">
                            <i class="fas fa-eye-slash"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Brouillons</div>
                        <div class="h5 mb-0">{{ $draftGalleries ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-info rounded-circle p-2 text-white">
                            <i class="fas fa-photo-video"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Médias totaux</div>
                        <div class="h5 mb-0">{{ $totalMedia ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.gallery.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recherche</label>
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
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="is_published" class="form-select">
                        <option value="">Tous</option>
                        <option value="yes" {{ request('is_published') == 'yes' ? 'selected' : '' }}>Publié</option>
                        <option value="no" {{ request('is_published') == 'no' ? 'selected' : '' }}>Brouillon</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
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

    <!-- Liste des galeries -->
    <div class="row g-4">
        @forelse($galleries as $gallery)
        <div class="col-md-4 col-lg-3">
            <div class="card shadow-sm h-100 hover-shadow transition">
                <a href="{{ route('admin.gallery.show', $gallery) }}" class="text-decoration-none">
                    <div class="position-relative">
                        @if($gallery->cover_image_url)
                            <img src="{{ $gallery->cover_image_url }}" 
                                 alt="{{ $gallery->title }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-images fa-4x text-white opacity-50"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-dark bg-opacity-75 px-3 py-2">
                                <i class="fas fa-{{ $gallery->category === 'trophies' ? 'trophy' : 'image' }} me-1"></i>
                                {{ $gallery->category_label }}
                            </span>
                        </div>
                        @if(!$gallery->is_published)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-eye-slash me-1"></i> Brouillon
                                </span>
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 start-0 m-2">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-photo-video me-1"></i> {{ $gallery->media_count }} médias
                            </span>
                        </div>
                    </div>
                </a>
                <div class="card-body">
                    <h6 class="fw-bold mb-1 text-dark">{{ Str::limit($gallery->title, 40) }}</h6>
                    @if($gallery->description)
                        <p class="text-muted small mb-2">{{ Str::limit($gallery->description, 60) }}</p>
                    @endif
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            {{ $gallery->created_at->format('d/m/Y') }}
                        </small>
                        <div class="d-flex gap-2">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i> {{ $gallery->views_count ?? 0 }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-heart me-1 text-danger"></i> {{ $gallery->likes_count ?? 0 }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> Voir
                        </a>
                        <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.gallery.toggle-publish', $gallery) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $gallery->is_published ? 'secondary' : 'success' }} btn-sm" 
                                    title="{{ $gallery->is_published ? 'Dépublier' : 'Publier' }}">
                                <i class="fas fa-{{ $gallery->is_published ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette galerie ?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                <h4>Aucune galerie trouvée</h4>
                <p class="text-muted">Commencez par créer une nouvelle galerie.</p>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Créer une galerie
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $galleries->links() }}
    </div>
</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
@endpush
@endsection
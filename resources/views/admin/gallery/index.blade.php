@extends('layouts.admin')

@section('title', 'Gestion des albums')

@section('page-title', 'Albums')
@section('page-subtitle', 'Gestion des albums photos et vidéos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Albums</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-images me-2 text-primary"></i>
            Albums
        </h1>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nouvel album
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.gallery.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recherche</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Titre...">
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

    <!-- Liste des albums -->
    <div class="row">
        @forelse($albums as $album)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm hover-shadow transition">
                <a href="{{ route('admin.gallery.show', $album) }}" class="text-decoration-none">
                    <div class="position-relative">
                        @if($album->cover_image_url)
                            <img src="{{ $album->cover_image_url }}" 
                                 alt="{{ $album->title }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-images fa-4x text-white opacity-50"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-dark bg-opacity-75 px-3 py-2">
                                <i class="fas fa-{{ $album->media_count > 0 ? 'image' : 'images' }} me-1"></i>
                                {{ $album->media_count }} médias
                            </span>
                        </div>
                        @if(!$album->is_published)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-secondary">Brouillon</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold mb-1">{{ Str::limit($album->title, 35) }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-tag me-1"></i> 
                                {{ $album->category_label }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $album->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                        @if($album->description)
                            <p class="text-muted small mt-2 mb-0">{{ Str::limit($album->description, 60) }}</p>
                        @endif
                    </div>
                </a>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i> {{ $album->views_count ?? 0 }}
                            </small>
                            <small class="text-muted ms-2">
                                <i class="fas fa-heart me-1 text-danger"></i> {{ $album->likes_count ?? 0 }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('admin.gallery.edit', $album) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $album) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet album ?')">
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
                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                <h4>Aucun album</h4>
                <p class="text-muted">Créez votre premier album photo</p>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Créer un album
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $albums->links() }}
    </div>
</div>

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
@endsection
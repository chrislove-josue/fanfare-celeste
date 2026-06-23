@extends('layouts.admin')

@section('title', 'Modifier la photo')

@section('page-title', 'Modifier la photo')
@section('page-subtitle', 'Modifier les informations de la photo')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Galerie</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier la photo
        </h1>
        <div>
            <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i> Voir
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Veuillez corriger les erreurs suivantes :
            </h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-8">
                        <!-- Image actuelle -->
                        @if($gallery->file_path)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Image actuelle</label>
                                <div>
                                    <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                                         alt="{{ $gallery->title }}" 
                                         style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Titre *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $gallery->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description', $gallery->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nouveau fichier</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" 
                                   accept="image/*,video/*">
                            <small class="text-muted">Laissez vide pour conserver le fichier actuel</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Catégorie *</label>
                                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($categories as $key => $label)
                                            <option value="{{ $key }}" {{ old('category', $gallery->category) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Type</label>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" {{ old('type', $gallery->type) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ordre</label>
                                    <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" 
                                           value="{{ old('order', $gallery->order ?? 0) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_published" value="0">
                                        <input type="checkbox" class="form-check-input" id="is_published" 
                                               name="is_published" value="1" {{ old('is_published', $gallery->is_published) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            <i class="fas fa-eye me-1"></i> Publié
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Texte alternatif</label>
                                    <input type="text" name="alt_text" class="form-control @error('alt_text') is-invalid @enderror" 
                                           value="{{ old('alt_text', $gallery->alt_text) }}" placeholder="Description SEO">
                                    @error('alt_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                                <div class="text-muted small">
                                    <div>Créé le : {{ $gallery->created_at->format('d/m/Y à H:i') }}</div>
                                    <div>Dernière modif : {{ $gallery->updated_at->format('d/m/Y à H:i') }}</div>
                                    <div>Vues : {{ $gallery->views_count ?? 0 }}</div>
                                    <div>Likes : {{ $gallery->likes_count ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
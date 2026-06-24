@extends('layouts.admin')

@section('title', 'Modifier la galerie')

@section('page-title', 'Modifier la galerie')
@section('page-subtitle', 'Modifier les informations de la galerie')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Galerie</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier la galerie
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
                        @if($gallery->cover_image_url)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Image de couverture actuelle</label>
                                <div>
                                    <img src="{{ $gallery->cover_image_url }}" 
                                         alt="{{ $gallery->title }}" 
                                         style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #ddd;">
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
                            <label class="form-label fw-semibold">Nouvelle image de couverture</label>
                            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" 
                                   accept="image/*">
                            <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                            @error('cover_image')
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
                                    <label class="form-label fw-semibold">Date de l'événement</label>
                                    <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror" 
                                           value="{{ old('event_date', optional($gallery->event_date)->format('Y-m-d')) }}">
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Lieu</label>
                                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                                           value="{{ old('location', $gallery->location) }}" placeholder="Lieu de l'événement">
                                    @error('location')
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

                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_published" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_published" 
                                           name="is_published" value="1" {{ old('is_published', $gallery->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        <i class="fas fa-eye me-1"></i> Publié
                                    </label>
                                </div>

                                <hr>
                                <div class="text-muted small">
                                    <div><strong>Créé le :</strong> {{ $gallery->created_at->format('d/m/Y à H:i') }}</div>
                                    <div><strong>Médias :</strong> {{ $gallery->media_count }}</div>
                                    <div><strong>Vues :</strong> {{ $gallery->views_count ?? 0 }}</div>
                                    <div><strong>Likes :</strong> {{ $gallery->likes_count ?? 0 }}</div>
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
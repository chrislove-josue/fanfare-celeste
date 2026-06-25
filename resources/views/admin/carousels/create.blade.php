@extends('layouts.admin')

@section('title', 'Ajouter un slide')

@section('page-title', 'Ajouter un slide')
@section('page-subtitle', 'Ajouter une nouvelle image au carousel')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.carousels.index') }}">Carousel</a></li>
    <li class="breadcrumb-item active">Ajouter</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus-circle me-2 text-primary"></i>
            Ajouter un slide
        </h1>
        <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
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
            <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Titre <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" placeholder="Titre du slide">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sous-titre <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" 
                                   value="{{ old('subtitle') }}" placeholder="Sous-titre du slide">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image *</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*" required>
                            <small class="text-muted">Formats acceptés : JPG, PNG, WEBP. Taille max : 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lien <span class="text-muted">(optionnel)</span></label>
                            <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" 
                                   value="{{ old('link') }}" placeholder="https://example.com/page">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Texte du lien <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="link_text" class="form-control @error('link_text') is-invalid @enderror" 
                                   value="{{ old('link_text') }}" placeholder="En savoir plus">
                            @error('link_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ordre d'affichage</label>
                                    <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" 
                                           value="{{ old('order', 0) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-eye me-1"></i> Actif
                                    </label>
                                    <div class="text-muted small">Le slide sera affiché sur le site</div>
                                </div>

                                <hr>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Conseil :</strong>
                                    <p class="mb-0 small">Utilisez une image de 1920x800 pixels pour un affichage optimal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Créer le slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
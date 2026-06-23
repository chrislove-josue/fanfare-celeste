@extends('layouts.admin')

@section('title', 'Créer un slide')

@section('page-title', 'Créer un slide')
@section('page-subtitle', 'Ajouter une nouvelle image au carousel')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.carousels.index') }}">Carousel</a></li>
    <li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    Nouveau slide
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.carousels.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Titre <span class="text-muted">(optionnel)</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Titre du slide">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Sous-titre <span class="text-muted">(optionnel)</span></label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" 
                               id="subtitle" name="subtitle" value="{{ old('subtitle') }}" 
                               placeholder="Sous-titre du slide">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image *</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*" required>
                        <div class="form-text">Format acceptés : JPG, PNG, WEBP. Taille max : 2MB</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Lien <span class="text-muted">(optionnel)</span></label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" 
                               id="link" name="link" value="{{ old('link') }}" 
                               placeholder="https://example.com/page">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="link_text" class="form-label">Texte du lien <span class="text-muted">(optionnel)</span></label>
                        <input type="text" class="form-control @error('link_text') is-invalid @enderror" 
                               id="link_text" name="link_text" value="{{ old('link_text') }}" 
                               placeholder="En savoir plus">
                        @error('link_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Actif</label>
                                <div class="text-muted small">Le slide sera affiché sur le site</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Créer le slide
                        </button>
                        <a href="{{ route('admin.carousels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-info"></i>
                    Conseils
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="fas fa-image text-primary me-2"></i>
                        <strong>Taille idéale :</strong>
                        <br>
                        <span class="text-muted small">1920 x 800 pixels pour un affichage optimal</span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-sort text-primary me-2"></i>
                        <strong>Ordre :</strong>
                        <br>
                        <span class="text-muted small">Les slides avec un ordre plus petit s'affichent en premier</span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-link text-primary me-2"></i>
                        <strong>Lien :</strong>
                        <br>
                        <span class="text-muted small">Ajoutez un lien pour rediriger vers une page spécifique</span>
                    </li>
                    <li>
                        <i class="fas fa-eye-slash text-primary me-2"></i>
                        <strong>Statut :</strong>
                        <br>
                        <span class="text-muted small">Désactivez un slide sans le supprimer</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Modifier le slide')

@section('page-title', 'Modifier le slide')
@section('page-subtitle', 'Modifier les informations du slide')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.carousels.index') }}">Carousel</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier le slide
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
            <form action="{{ route('admin.carousels.update', $carousel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($carousel->image)
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Image actuelle</label>
                            <div>
                                <img src="{{ asset('storage/' . $carousel->image) }}" 
                                     alt="{{ $carousel->title }}" 
                                     style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Titre <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $carousel->title) }}" placeholder="Titre du slide">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sous-titre <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" 
                                   value="{{ old('subtitle', $carousel->subtitle) }}" placeholder="Sous-titre du slide">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nouvelle image <span class="text-muted">(optionnel)</span></label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*">
                            <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lien <span class="text-muted">(optionnel)</span></label>
                            <input type="url" name="link" class="form-control @error('link') is-invalid @enderror" 
                                   value="{{ old('link', $carousel->link) }}" placeholder="https://example.com/page">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Texte du lien <span class="text-muted">(optionnel)</span></label>
                            <input type="text" name="link_text" class="form-control @error('link_text') is-invalid @enderror" 
                                   value="{{ old('link_text', $carousel->link_text) }}" placeholder="En savoir plus">
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
                                           value="{{ old('order', $carousel->order ?? 0) }}" min="0">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-eye me-1"></i> Actif
                                    </label>
                                    <div class="text-muted small">Le slide sera affiché sur le site</div>
                                </div>

                                <hr>
                                <div class="text-muted small">
                                    <div><strong>Créé le :</strong> {{ $carousel->created_at->format('d/m/Y à H:i') }}</div>
                                    <div><strong>Dernière modification :</strong> {{ $carousel->updated_at->format('d/m/Y à H:i') }}</div>
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
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
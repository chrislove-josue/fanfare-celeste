@extends('layouts.admin')

@section('title', 'Modifier le slide')

@section('page-title', 'Modifier le slide')
@section('page-subtitle', 'Modifier les informations du slide')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.carousels.index') }}">Carousel</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-warning"></i>
                    Modifier le slide
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.carousels.update', $carousel) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if($carousel->image)
                        <div class="mb-3">
                            <label class="form-label">Image actuelle</label>
                            <div>
                                <img src="{{ asset('storage/' . $carousel->image) }}" 
                                     alt="{{ $carousel->title }}" 
                                     style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label">Titre <span class="text-muted">(optionnel)</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $carousel->title) }}" 
                               placeholder="Titre du slide">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Sous-titre <span class="text-muted">(optionnel)</span></label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" 
                               id="subtitle" name="subtitle" value="{{ old('subtitle', $carousel->subtitle) }}" 
                               placeholder="Sous-titre du slide">
                        @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Nouvelle image <span class="text-muted">(optionnel)</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="form-text">Laissez vide pour conserver l'image actuelle</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="link" class="form-label">Lien <span class="text-muted">(optionnel)</span></label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" 
                               id="link" name="link" value="{{ old('link', $carousel->link) }}" 
                               placeholder="https://example.com/page">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="link_text" class="form-label">Texte du lien <span class="text-muted">(optionnel)</span></label>
                        <input type="text" class="form-control @error('link_text') is-invalid @enderror" 
                               id="link_text" name="link_text" value="{{ old('link_text', $carousel->link_text) }}" 
                               placeholder="En savoir plus">
                        @error('link_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $carousel->order ?? 0) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $carousel->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Actif</label>
                                <div class="text-muted small">Le slide sera affiché sur le site</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Mettre à jour
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
                    Informations
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Créé le :</strong>
                    <br>
                    <span class="text-muted">{{ $carousel->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="mb-3">
                    <strong>Dernière modification :</strong>
                    <br>
                    <span class="text-muted">{{ $carousel->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div>
                    <strong>ID :</strong>
                    <br>
                    <span class="text-muted">#{{ $carousel->id }}</span>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                    Astuce
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-0">
                    Pour une meilleure visibilité, utilisez des images de qualité 
                    avec un rapport 16:9 (ex: 1920x1080).
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
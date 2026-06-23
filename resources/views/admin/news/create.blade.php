@extends('layouts.admin')

@section('title', 'Créer une actualité')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus-circle me-2 text-primary"></i>
            Créer une actualité
        </h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
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
            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Titre -->
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-heading me-1 text-primary"></i>
                            Titre *
                        </label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" placeholder="Titre de l'actualité" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-toggle-on me-1 text-primary"></i>
                            Statut
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>✅ Publié</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Extrait -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-align-left me-1 text-primary"></i>
                            Extrait
                        </label>
                        <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" 
                                  rows="2" placeholder="Résumé de l'article...">{{ old('excerpt') }}</textarea>
                        <small class="text-muted">Un résumé court qui apparaîtra dans la liste des actualités</small>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contenu -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-alt me-1 text-primary"></i>
                            Contenu *
                        </label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" 
                                  rows="10" placeholder="Rédigez votre article ici..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image à la une -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-image me-1 text-primary"></i>
                            Image à la une
                        </label>
                        <input type="file" name="featured_image" class="form-control @error('featured_image') is-invalid @enderror" 
                               accept="image/*">
                        <small class="text-muted">Format : JPG, PNG, WEBP | Taille max : 2MB</small>
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date de publication -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                            Date de publication
                        </label>
                        <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" 
                               value="{{ old('published_at') }}">
                        <small class="text-muted">Laissez vide pour la date actuelle</small>
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="button" onclick="window.location.href='{{ route('admin.news.index') }}'" 
                            class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Créer l'actualité
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
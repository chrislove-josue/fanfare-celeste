@extends('layouts.admin')

@section('title', 'Modifier l\'actualité')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier l'actualité
        </h1>
        <div>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('public.news.show', $news->slug) }}" target="_blank" class="btn btn-info">
                <i class="fas fa-eye me-2"></i> Voir sur le site
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Titre -->
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-heading me-1 text-primary"></i>
                            Titre *
                        </label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $news->title) }}" placeholder="Titre de l'actualité" required>
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
                            <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                            <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>✅ Publié</option>
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
                                  rows="2" placeholder="Résumé de l'article...">{{ old('excerpt', $news->excerpt) }}</textarea>
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
                                  rows="10" placeholder="Rédigez votre article ici..." required>{{ old('content', $news->content) }}</textarea>
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
                        
                        @if($news->featured_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $news->featured_image) }}" 
                                     alt="{{ $news->title }}" 
                                     style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                        @endif
                        
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
                               value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}">
                        <small class="text-muted">
                            @if($news->published_at)
                                Publié le : {{ $news->published_at->format('d/m/Y à H:i') }}
                            @else
                                Laissez vide pour la date actuelle
                            @endif
                        </small>
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Slug (lecture seule) -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-link me-1 text-primary"></i>
                            Slug
                        </label>
                        <input type="text" class="form-control bg-light" value="{{ $news->slug }}" readonly disabled>
                        <small class="text-muted">Le slug est généré automatiquement à partir du titre</small>
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="col-md-12">
                        <hr>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="fw-semibold">Créé le</label>
                                <p class="mb-0 text-muted">{{ $news->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Dernière modification</label>
                                <p class="mb-0 text-muted">{{ $news->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">ID</label>
                                <p class="mb-0 text-muted">#{{ $news->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    
                    @if($news->status === 'published')
                        <a href="{{ route('public.news.show', $news->slug) }}" target="_blank" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i> Voir sur le site
                        </a>
                    @endif
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
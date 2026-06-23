@extends('layouts.admin')

@section('title', 'Créer un témoignage')

@section('page-title', 'Créer un témoignage')
@section('page-subtitle', 'Ajouter un nouveau témoignage')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Témoignages</a></li>
    <li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    Nouveau témoignage
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="author_name" class="form-label">Nom de l'auteur *</label>
                        <input type="text" class="form-control @error('author_name') is-invalid @enderror" 
                               id="author_name" name="author_name" value="{{ old('author_name') }}" 
                               placeholder="Jean Dupont" required>
                        @error('author_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author_role" class="form-label">Rôle / Fonction</label>
                        <input type="text" class="form-control @error('author_role') is-invalid @enderror" 
                               id="author_role" name="author_role" value="{{ old('author_role') }}" 
                               placeholder="Membre actif, Chef de section, etc.">
                        @error('author_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Témoignage *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="5" 
                                  placeholder="Partagez votre expérience..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author_image" class="form-label">Photo de l'auteur</label>
                        <input type="file" class="form-control @error('author_image') is-invalid @enderror" 
                               id="author_image" name="author_image" accept="image/*">
                        <div class="form-text">Format acceptés : JPG, PNG. Taille max : 2MB</div>
                        @error('author_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label">Note (1-5 étoiles)</label>
                            <select class="form-select @error('rating') is-invalid @enderror" 
                                    id="rating" name="rating">
                                <option value="">Sans note</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="order" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Actif</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_approved" value="0">
                                <input type="checkbox" class="form-check-input" id="is_approved" 
                                       name="is_approved" value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_approved">Approuvé</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Créer le témoignage
                        </button>
                        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-user text-primary me-2"></i>
                        <strong>Authenticité :</strong>
                        <br>
                        <span class="text-muted small">Utilisez des témoignages réels de membres</span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-star text-warning me-2"></i>
                        <strong>Note :</strong>
                        <br>
                        <span class="text-muted small">Les témoignages avec note apparaissent en premier</span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-image text-primary me-2"></i>
                        <strong>Photo :</strong>
                        <br>
                        <span class="text-muted small">Une photo rend le témoignage plus crédible</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Approbation :</strong>
                        <br>
                        <span class="text-muted small">Approuvez les témoignages avant publication</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
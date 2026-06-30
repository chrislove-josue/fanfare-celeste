@extends('layouts.admin')

@section('title', 'Créer un événement')

@section('page-title', 'Créer un événement')
@section('page-subtitle', 'Ajouter un nouvel événement')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Actualités & Événements</a></li>
    <li class="breadcrumb-item active">Créer un événement</li>
@endsection

@section('content')
<div class="shadow-sm card">
    <div class="card-body">
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

        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <!-- Titre -->
                <div class="col-md-8">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-heading me-1 text-primary"></i>
                        Titre *
                    </label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-tag me-1 text-primary"></i>
                        Type *
                    </label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">Sélectionner un type</option>
                        @foreach($types as $key => $value)
                            <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-align-left me-1 text-primary"></i>
                        Description *
                    </label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="5" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        Date de début *
                    </label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        Date de fin *
                    </label>
                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lieu -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                        Lieu *
                    </label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                           value="{{ old('location') }}" required>
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-link me-1 text-primary"></i>
                        URL du lieu
                    </label>
                    <input type="url" name="location_url" class="form-control @error('location_url') is-invalid @enderror" 
                           value="{{ old('location_url') }}" placeholder="https://maps.google.com/...">
                    @error('location_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Responsable -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-user me-1 text-primary"></i>
                        Responsable
                    </label>
                    <select name="responsible_id" class="form-select @error('responsible_id') is-invalid @enderror">
                        <option value="">Aucun</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('responsible_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Budget -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-money-bill me-1 text-primary"></i>
                        Budget
                    </label>
                    <input type="number" name="budget" class="form-control @error('budget') is-invalid @enderror" 
                           value="{{ old('budget') }}" step="0.01" min="0">
                    @error('budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Affiche -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-image me-1 text-primary"></i>
                        Affiche
                    </label>
                    <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror" 
                           accept="image/*">
                    <small class="text-muted">Format : JPG, PNG, WEBP | Taille max : 2MB</small>
                    @error('poster')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-sticky-note me-1 text-primary"></i>
                        Notes
                    </label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                              rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="gap-2 pt-3 mt-4 d-flex justify-content-end border-top">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Créer l'événement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
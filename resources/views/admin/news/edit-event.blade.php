@extends('layouts.admin')

@section('title', 'Modifier l\'événement')

@section('page-title', 'Modifier l\'événement')
@section('page-subtitle', $event->title)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Actualités & Événements</a></li>
    <li class="breadcrumb-item active">Modifier</li>
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

        <form method="POST" action="{{ route('admin.news.update-event', $event) }}" enctype="multipart/form-data">
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
                           value="{{ old('title', $event->title) }}" required>
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
                        @foreach($types as $key => $value)
                            <option value="{{ $key }}" {{ old('type', $event->type) === $key ? 'selected' : '' }}>
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
                              rows="5" required>{{ old('description', $event->description) }}</textarea>
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
                           value="{{ old('start_date', optional($event->start_date)->format('Y-m-d\TH:i')) }}" required>
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
                           value="{{ old('end_date', optional($event->end_date)->format('Y-m-d\TH:i')) }}" required>
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
                           value="{{ old('location', $event->location) }}" required>
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
                           value="{{ old('location_url', $event->location_url) }}" placeholder="https://maps.google.com/...">
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
                            <option value="{{ $user->id }}" {{ old('responsible_id', $event->responsible_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Statut -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-toggle-on me-1 text-primary"></i>
                        Statut *
                    </label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statuses as $key => $value)
                            <option value="{{ $key }}" {{ old('status', $event->status) === $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
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
                           value="{{ old('budget', $event->budget) }}" step="0.01" min="0">
                    @error('budget')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-receipt me-1 text-primary"></i>
                        Coût réel
                    </label>
                    <input type="number" name="actual_cost" class="form-control @error('actual_cost') is-invalid @enderror" 
                           value="{{ old('actual_cost', $event->actual_cost) }}" step="0.01" min="0">
                    @error('actual_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Affiche -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-image me-1 text-primary"></i>
                        Affiche
                    </label>
                    
                    @if($event->poster_path)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $event->poster_path) }}" 
                                 alt="{{ $event->title }}" 
                                 style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #ddd;">
                            <div class="mt-1">
                                <small class="text-muted">Affiche actuelle</small>
                            </div>
                        </div>
                    @endif
                    
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
                              rows="3">{{ old('notes', $event->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Informations supplémentaires -->
                <div class="col-md-12">
                    <hr>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="fw-semibold">Créé le</label>
                            <p class="mb-0 text-muted">{{ $event->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold">Dernière modification</label>
                            <p class="mb-0 text-muted">{{ $event->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold">ID</label>
                            <p class="mb-0 text-muted">#{{ $event->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="gap-2 pt-3 mt-4 d-flex justify-content-end border-top">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Annuler
                </a>
                
                <a href="{{ route('admin.news.show-event', $event) }}" class="btn btn-info">
                    <i class="fas fa-eye me-2"></i> Voir le détail
                </a>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Modifier le membre')

@section('page-title', 'Modifier le membre')
@section('page-subtitle', 'Modifier les informations du membre')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier le membre
        </h1>
        <div>
            <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('admin.members.card', $member) }}" class="btn btn-primary">
                <i class="fas fa-id-card me-2"></i> Carte
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
            <form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Photo -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Photo</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format : JPG, PNG | Taille max : 2MB</small>
                        @if($member->photo_url)
                            <div class="mt-2">
                                <img src="{{ $member->photo_url }}" 
                                     class="rounded-circle" 
                                     width="100" height="100" 
                                     style="object-fit: cover; border: 3px solid #3946B2;" 
                                     alt="Photo membre">
                            </div>
                        @endif
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Numéro de membre -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">N° Membre</label>
                        <input type="text" class="form-control bg-light" value="{{ $member->member_number }}" disabled readonly>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Statut *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $member->status) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prénom *</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name', $member->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nom *</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name', $member->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email (lecture seule) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $member->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Téléphone *</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $member->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- WhatsApp -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                               value="{{ old('whatsapp', $member->whatsapp) }}">
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Section</label>
                        <select name="section" class="form-select @error('section') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($sections as $key => $label)
                                <option value="{{ $key }}" {{ old('section', $member->section) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rôle dans la section -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rôle dans la section</label>
                        <input type="text" name="section_role" class="form-control @error('section_role') is-invalid @enderror" 
                               value="{{ old('section_role', $member->section_role) }}" placeholder="Responsable, Membre...">
                        @error('section_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Instrument -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Instrument</label>
                        <select name="instrument" class="form-select @error('instrument') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($instruments as $key => $label)
                                <option value="{{ $key }}" {{ old('instrument', $member->instrument) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('instrument')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveau instrument -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Niveau instrument</label>
                        <select name="instrument_level" class="form-select @error('instrument_level') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($instrumentLevels as $key => $label)
                                <option value="{{ $key }}" {{ old('instrument_level', $member->instrument_level) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('instrument_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date d'entrée -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date d'entrée</label>
                        <input type="date" name="entry_date" class="form-control @error('entry_date') is-invalid @enderror" 
                               value="{{ old('entry_date', optional($member->entry_date)->format('Y-m-d')) }}">
                        @error('entry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Adresse</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $member->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date de naissance</label>
                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                               value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lieu de naissance -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Lieu de naissance</label>
                        <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" 
                               value="{{ old('birth_place', $member->birth_place) }}">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact d'urgence (Nom)</label>
                        <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                               value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                        @error('emergency_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact d'urgence (Téléphone)</label>
                        <input type="text" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                               value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                        @error('emergency_contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes médicales -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Notes médicales</label>
                        <textarea name="medical_notes" class="form-control @error('medical_notes') is-invalid @enderror" rows="2">{{ old('medical_notes', $member->medical_notes) }}</textarea>
                        @error('medical_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Compétences -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Compétences</label>
                        <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror" 
                               value="{{ old('skills', $member->skills) }}" placeholder="Séparées par des virgules">
                        @error('skills')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes admin -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Notes (Admin)</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $member->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Informations système -->
                    <div class="col-md-12 mt-3">
                        <hr>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="fw-semibold">Créé le</label>
                                <p class="mb-0 text-muted">{{ $member->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Dernière modification</label>
                                <p class="mb-0 text-muted">{{ $member->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">ID</label>
                                <p class="mb-0 text-muted">#{{ $member->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary">
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
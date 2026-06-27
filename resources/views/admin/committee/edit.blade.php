{{-- resources/views/admin/committee/edit.blade.php --}}

@extends('layouts.admin')

@section('title', 'Modifier le membre du comité')

@section('page-title', 'Modifier le membre du comité')
@section('page-subtitle', $committeeMember->member ? $committeeMember->member->full_name : 'Membre inconnu')

{{-- ✅ CORRECTION : Breadcrumb avec paramètre explicite --}}
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.committee.index') }}">Comité</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<style>
    .form-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }
    
    .form-section .section-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f3f5;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-section .section-title i {
        color: #3946B2;
    }
    
    .form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: #374151;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3946B2;
        box-shadow: 0 0 0 3px rgba(57, 70, 178, 0.1);
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
    
    .invalid-feedback {
        font-size: 0.8rem;
        color: #dc2626;
    }
    
    .btn-action {
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    .member-info-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }
    
    .member-info-card .member-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef2f6;
    }
    
    .member-info-card .member-avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .member-info-card .member-name {
        font-weight: 600;
        font-size: 1rem;
        color: #1a1a2e;
    }
    
    .member-info-card .member-info {
        font-size: 0.85rem;
        color: #6b7280;
    }
</style>

<div class="container-fluid py-4">
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

    {{-- ✅ LIGNE 33 CORRIGÉE : Utilisation de l'ID explicite --}}
    <form method="POST" action="{{ route('admin.committee.update', ['committee' => $committeeMember->id]) }}">
        @csrf
        @method('PUT')

        <!-- ========================================== -->
        <!-- SECTION 1 : INFORMATIONS DU MEMBRE         -->
        <!-- ========================================== -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-user"></i>
                Membre
            </div>
            
            <div class="member-info-card">
                <div>
                    @if($committeeMember->member && $committeeMember->member->photo)
                        <img src="{{ asset('storage/' . $committeeMember->member->photo) }}" 
                             class="member-avatar" 
                             alt="{{ $committeeMember->member->full_name }}">
                    @else
                        <div class="member-avatar-placeholder">
                            {{ $committeeMember->member ? strtoupper(substr($committeeMember->member->first_name, 0, 1)) : '?' }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="member-name">
                        {{ $committeeMember->member ? $committeeMember->member->full_name : 'Membre inconnu' }}
                    </div>
                    <div class="member-info">
                        {{ $committeeMember->member ? $committeeMember->member->member_number : '-' }}
                        @if($committeeMember->member && $committeeMember->member->section)
                            • {{ $committeeMember->member->section }}
                        @endif
                    </div>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-{{ $committeeMember->is_current ? 'success' : 'secondary' }}">
                        {{ $committeeMember->is_current ? 'En fonction' : 'Mandat terminé' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 2 : INFORMATIONS DU POSTE          -->
        <!-- ========================================== -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-briefcase"></i>
                Informations du poste
            </div>
            <div class="row g-3">
                <!-- Position -->
                <div class="col-md-6">
                    <label class="form-label">Poste *</label>
                    <select name="position" class="form-select @error('position') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un poste --</option>
                        @foreach($positions as $key => $label)
                            <option value="{{ $key }}" {{ old('position', $committeeMember->position) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de début -->
                <div class="col-md-6">
                    <label class="form-label">Date de début du mandat *</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date', optional($committeeMember->start_date)->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ordre d'affichage -->
                <div class="col-md-6">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="position_order" class="form-control @error('position_order') is-invalid @enderror" 
                           value="{{ old('position_order', $committeeMember->position_order) }}" min="1" max="99">
                    <small class="text-muted">Détermine l'ordre d'affichage dans le bureau (1 = en premier)</small>
                    @error('position_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Responsabilités -->
                <div class="col-md-12">
                    <label class="form-label">Responsabilités</label>
                    <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="3">{{ old('responsibilities', $committeeMember->responsibilities) }}</textarea>
                    <small class="text-muted">Décrivez les responsabilités et missions de ce membre au sein du bureau</small>
                    @error('responsibilities')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Est actif -->
                <div class="col-md-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_current" class="form-check-input" id="is_current" value="1" {{ old('is_current', $committeeMember->is_current) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_current">
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            Actuellement en fonction
                        </label>
                        <small class="text-muted d-block">Décochez si ce mandat est terminé</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- BOUTONS D'ACTION                            -->
        <!-- ========================================== -->
        <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('admin.committee.index') }}" class="btn btn-secondary btn-action">
                <i class="fas fa-times me-2"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary btn-action">
                <i class="fas fa-save me-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
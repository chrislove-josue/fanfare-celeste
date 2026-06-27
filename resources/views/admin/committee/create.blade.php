{{-- resources/views/admin/committee/create.blade.php --}}

@extends('layouts.admin')

@section('title', 'Ajouter un membre au comité')

@section('page-title', 'Ajouter un membre au comité')
@section('page-subtitle', 'Constituer le bureau exécutif de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.committee.index') }}">Comité</a></li>
    <li class="breadcrumb-item active">Ajouter</li>
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
    
    .member-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #fafafa;
    }
    
    .member-card:hover {
        border-color: #3946B2;
        background: #eef2ff;
    }
    
    .member-card.selected {
        border-color: #3946B2;
        background: #eef2ff;
        box-shadow: 0 0 0 3px rgba(57, 70, 178, 0.1);
    }
    
    .member-card .member-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef2f6;
    }
    
    .member-card .member-avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .member-card .member-name {
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .member-card .member-info {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .member-card .member-check {
        margin-left: auto;
        color: #3946B2;
        display: none;
    }
    
    .member-card.selected .member-check {
        display: block;
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

    <form method="POST" action="{{ route('admin.committee.store') }}">
        @csrf

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-user-check"></i>
                Sélectionner un membre
            </div>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Rechercher un membre *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="memberSearch" class="form-control" placeholder="Tapez le nom, prénom ou numéro de membre...">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="text-muted small mb-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Cliquez sur un membre pour le sélectionner
                        </div>
                        <div class="row g-2" id="memberList">
                            @foreach($availableMembers as $member)
                            <div class="col-md-6 col-lg-4">
                                <div class="member-card" onclick="selectMember(this)">
                                    <div>
                                        @if($member->photo)
                                            <img src="{{ asset('storage/' . $member->photo) }}" class="member-avatar" alt="{{ $member->full_name }}">
                                        @else
                                            <div class="member-avatar-placeholder">
                                                {{ strtoupper(substr($member->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="member-name">{{ $member->full_name }}</div>
                                        <div class="member-info">
                                            {{ $member->member_number }} • {{ $member->section ?? 'Sans section' }}
                                        </div>
                                    </div>
                                    <div class="member-check">
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                    <input type="radio" name="member_id" value="{{ $member->id }}" style="display: none;" required>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @error('member_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-briefcase"></i>
                Informations du poste
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Poste *</label>
                    <select name="position" class="form-select @error('position') is-invalid @enderror" required>
                        <option value="">-- Sélectionner un poste --</option>
                        @foreach($positions as $key => $label)
                            <option value="{{ $key }}" {{ old('position') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de début du mandat *</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" name="position_order" class="form-control @error('position_order') is-invalid @enderror" 
                           value="{{ old('position_order', $nextOrder ?? 1) }}" min="1" max="99">
                    <small class="text-muted">Détermine l'ordre d'affichage (1 = en premier)</small>
                    @error('position_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Responsabilités</label>
                    <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="3">{{ old('responsibilities') }}</textarea>
                    @error('responsibilities')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_current" class="form-check-input" id="is_current" value="1" {{ old('is_current', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_current">
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            Actuellement en fonction
                        </label>
                        <small class="text-muted d-block">Décochez si ce mandat est terminé</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('admin.committee.index') }}" class="btn btn-secondary btn-action">
                <i class="fas fa-times me-2"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary btn-action">
                <i class="fas fa-save me-2"></i> Ajouter au comité
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function selectMember(element) {
        document.querySelectorAll('.member-card').forEach(card => {
            card.classList.remove('selected');
            card.querySelector('input[type="radio"]').checked = false;
        });
        
        element.classList.add('selected');
        const radio = element.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('memberSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                document.querySelectorAll('.member-card').forEach(card => {
                    const name = card.querySelector('.member-name')?.textContent?.toLowerCase() || '';
                    const info = card.querySelector('.member-info')?.textContent?.toLowerCase() || '';
                    card.closest('.col-md-6').style.display = (name.includes(query) || info.includes(query)) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
@endsection
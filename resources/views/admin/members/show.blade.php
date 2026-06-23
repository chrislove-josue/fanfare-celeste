@extends('layouts.admin')

@section('title', $member->full_name)

@section('page-title', $member->full_name)
@section('page-subtitle', 'Détails du membre')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item active">{{ $member->full_name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Carte d'identité -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" 
                         alt="{{ $member->full_name }}" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #3946B2;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 120px; height: 120px; font-size: 40px; font-weight: 700;">
                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                    </div>
                @endif
                
                <h5 class="mt-3 mb-1">{{ $member->full_name }}</h5>
                <p class="text-muted small">#{{ $member->member_number }}</p>
                
                <div class="d-flex justify-content-center gap-2">
                    @if($member->status === 'active')
                        <span class="badge bg-success">Actif</span>
                    @elseif($member->status === 'pending')
                        <span class="badge bg-warning">En attente</span>
                    @else
                        <span class="badge bg-secondary">Inactif</span>
                    @endif
                    
                    @if($member->section)
                        <span class="badge bg-primary">{{ $member->section }}</span>
                    @endif
                </div>
                
                <hr>
                
                <div class="text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Email</span>
                        <span>{{ $member->email ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Téléphone</span>
                        <span>{{ $member->phone ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">WhatsApp</span>
                        <span>{{ $member->whatsapp ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Date de naissance</span>
                        <span>{{ $member->birth_date ? $member->birth_date->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Âge</span>
                        <span>{{ $member->age ?? '-' }} ans</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Date d'entrée</span>
                        <span>{{ $member->entry_date ? $member->entry_date->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Membre depuis</span>
                        <span>{{ $member->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Modifier
                    </a>
                    <a href="{{ route('admin.members.card', $member) }}" target="_blank" class="btn btn-success">
                        <i class="fas fa-id-card me-2"></i> Carte de membre
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informations détaillées -->
    <div class="col-lg-8">
        <!-- Instrument et section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-music me-2 text-primary"></i>
                    Informations musicales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Section</span>
                            <span class="fw-semibold">{{ $member->section ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Rôle</span>
                            <span class="fw-semibold">{{ $member->section_role ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Instrument</span>
                            <span class="fw-semibold">{{ $member->instrument ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Niveau</span>
                            <span class="fw-semibold">{{ $member->instrument_level ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact d'urgence -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-phone-alt me-2 text-danger"></i>
                    Contact d'urgence
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Nom</span>
                            <span class="fw-semibold">{{ $member->emergency_contact_name ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Téléphone</span>
                            <span class="fw-semibold">{{ $member->emergency_contact_phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @if($member->medical_notes)
                    <div class="mt-2">
                        <span class="text-muted">Notes médicales</span>
                        <p class="mt-1 mb-0">{{ $member->medical_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Compétences et notes -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2 text-warning"></i>
                    Compétences & Notes
                </h5>
            </div>
            <div class="card-body">
                @if($member->skills)
                    <div class="mb-3">
                        <span class="text-muted">Compétences</span>
                        <div class="mt-1">
                            @foreach(explode(',', $member->skills) as $skill)
                                <span class="badge bg-light text-dark me-1">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($member->notes)
                    <div>
                        <span class="text-muted">Notes</span>
                        <p class="mt-1 mb-0">{{ $member->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
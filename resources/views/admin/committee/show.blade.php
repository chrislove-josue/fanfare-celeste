{{-- resources/views/admin/committee/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Détails du membre du comité')

@section('page-title', 'Détails du membre du comité')
@section('page-subtitle', $committeeMember->member ? $committeeMember->member->full_name : 'Membre inconnu')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.committee.index') }}">Comité</a></li>
    <li class="breadcrumb-item active">Détails</li>
@endsection

@section('content')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        padding: 2rem 1.5rem;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3946B2, #6366f1, #8b5cf6);
    }
    
    .profile-card .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        margin: 0 auto;
    }
    
    .profile-card .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        margin: 0 auto;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }
    
    .position-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }
    
    .position-badge.president { background: #fee2e2; color: #dc2626; }
    .position-badge.vice_president { background: #fef3c7; color: #d97706; }
    .position-badge.general_secretary { background: #dbeafe; color: #2563eb; }
    .position-badge.treasurer { background: #dcfce7; color: #16a34a; }
    .position-badge.conductor { background: #fce7f3; color: #db2777; }
    .position-badge.section_leader { background: #ede9fe; color: #7c3aed; }
    .position-badge.default { background: #e5e7eb; color: #6b7280; }
    
    .info-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #f1f3f5;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        border-color: #3946B2;
        box-shadow: 0 4px 16px rgba(57, 70, 178, 0.1);
    }
    
    .info-card .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        font-weight: 600;
    }
    
    .info-card .value {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-top: 4px;
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
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-user-tie me-2 text-primary"></i>
            Détails du membre du comité
        </h1>
        <div>
            <a href="{{ route('admin.committee.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            
            {{-- ✅ LIGNE 136 CORRIGÉE : Syntaxe explicite avec tableau --}}
            <a href="{{ route('admin.committee.edit', ['committee' => $committeeMember->id]) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
            
            @if($committeeMember->is_current)
                <form action="{{ route('admin.committee.end', ['committee' => $committeeMember->id]) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info" onclick="return confirm('Terminer le mandat de ce membre ?')">
                        <i class="fas fa-check-double me-2"></i> Terminer le mandat
                    </button>
                </form>
            @endif
            
            <form action="{{ route('admin.committee.destroy', ['committee' => $committeeMember->id]) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce membre du comité ?')">
                    <i class="fas fa-trash me-2"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="profile-card">
                @if($committeeMember->member && $committeeMember->member->photo)
                    <img src="{{ asset('storage/' . $committeeMember->member->photo) }}" 
                         class="profile-avatar" 
                         alt="{{ $committeeMember->member->full_name }}">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ $committeeMember->member ? strtoupper(substr($committeeMember->member->first_name, 0, 1)) : '?' }}
                    </div>
                @endif
                
                <h4 class="mt-3 mb-1">
                    {{ $committeeMember->member ? $committeeMember->member->full_name : 'Membre inconnu' }}
                </h4>
                <p class="text-muted small">
                    {{ $committeeMember->member ? $committeeMember->member->member_number : '-' }}
                </p>
                
                <div class="mt-3">
                    <span class="position-badge {{ $committeeMember->position ?? 'default' }}">
                        {{ $positions[$committeeMember->position] ?? $committeeMember->position }}
                    </span>
                    <span class="badge bg-{{ $committeeMember->is_current ? 'success' : 'secondary' }} ms-2">
                        {{ $committeeMember->is_current ? 'En fonction' : 'Mandat terminé' }}
                    </span>
                </div>
                
                @if($committeeMember->member)
                    <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                        @if($committeeMember->member->section)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill">
                                <i class="fas fa-users me-1"></i> {{ $committeeMember->member->section }}
                            </span>
                        @endif
                        @if($committeeMember->member->instrument)
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                <i class="fas fa-music me-1"></i> {{ $committeeMember->member->instrument }}
                            </span>
                        @endif
                    </div>
                @endif
                
                <div class="mt-3">
                    <a href="{{ $committeeMember->member ? route('admin.members.show', $committeeMember->member) : '#' }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-right me-1"></i> Voir le profil complet
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="label"><i class="fas fa-calendar-alt me-1"></i> Date de début</div>
                        <div class="value">{{ $committeeMember->start_date ? $committeeMember->start_date->format('d/m/Y') : '-' }}</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="label"><i class="fas fa-calendar-check me-1"></i> Date de fin</div>
                        <div class="value">{{ $committeeMember->end_date ? $committeeMember->end_date->format('d/m/Y') : 'En cours' }}</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="label"><i class="fas fa-clock me-1"></i> Durée du mandat</div>
                        <div class="value">
                            @if($committeeMember->start_date)
                                @php
                                    $end = $committeeMember->end_date ?? now();
                                    $diff = $committeeMember->start_date->diff($end);
                                @endphp
                                {{ $diff->y > 0 ? $diff->y . ' an' . ($diff->y > 1 ? 's' : '') . ' ' : '' }}
                                {{ $diff->m > 0 ? $diff->m . ' mois' : '' }}
                                {{ $diff->y == 0 && $diff->m == 0 ? $diff->d . ' jours' : '' }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="label"><i class="fas fa-sort-numeric-down me-1"></i> Ordre d'affichage</div>
                        <div class="value">{{ $committeeMember->position_order ?? 'Non défini' }}</div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="info-card">
                        <div class="label"><i class="fas fa-tasks me-1"></i> Responsabilités</div>
                        <div class="value" style="font-weight: 400; line-height: 1.6;">
                            {{ $committeeMember->responsibilities ?? 'Aucune responsabilité définie' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="info-card">
                        <div class="label"><i class="fas fa-trophy me-1 text-warning"></i> Réalisations</div>
                        <div class="value" style="font-weight: 400; line-height: 1.6;">
                            {{ $committeeMember->achievements ?? 'Aucune réalisation enregistrée' }}
                        </div>
                    </div>
                </div>

                @if($committeeMember->member)
                    <div class="col-md-12">
                        <div class="info-card">
                            <div class="label"><i class="fas fa-user me-1"></i> Informations du membre</div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between py-1" style="border-bottom: 1px solid #f1f3f5;">
                                        <span class="text-muted small">Email</span>
                                        <span>{{ $committeeMember->member->email ?? '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1" style="border-bottom: 1px solid #f1f3f5;">
                                        <span class="text-muted small">Téléphone</span>
                                        <span>{{ $committeeMember->member->phone ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between py-1" style="border-bottom: 1px solid #f1f3f5;">
                                        <span class="text-muted small">Statut</span>
                                        <span class="badge bg-{{ $committeeMember->member->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($committeeMember->member->status) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1">
                                        <span class="text-muted small">Entrée</span>
                                        <span>{{ $committeeMember->member->entry_date ? $committeeMember->member->entry_date->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
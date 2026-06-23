@extends('layouts.admin')

@section('title', 'Mon profil')

@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérer vos informations personnelles')
@section('breadcrumb')
    <li class="breadcrumb-item active">Mon profil</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                @if($member && $member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" 
                         alt="{{ $user->name }}" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #3946B2;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 120px; height: 120px; font-size: 48px; font-weight: 700;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                
                <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
                <p class="text-muted small">{{ $user->email }}</p>
                
                @if($member)
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        @if($member->status === 'active')
                            <span class="badge bg-success">Membre actif</span>
                        @elseif($member->status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                        
                        @if($member->section)
                            <span class="badge bg-primary">{{ $member->section }}</span>
                        @endif
                        
                        @if($currentCommitteePosition)
                            <span class="badge bg-info">{{ $currentCommitteePosition->position }}</span>
                        @endif
                    </div>
                    <p class="text-muted small mt-2">#{{ $member->member_number }}</p>
                @else
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        Vous n'êtes pas encore associé à un membre.
                    </div>
                @endif
                
                <hr>
                
                <div class="text-start">
                    @if($member)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Téléphone</span>
                            <span>{{ $member->phone ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Adresse</span>
                            <span>{{ $member->address ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Section</span>
                            <span>{{ $member->section ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Instrument</span>
                            <span>{{ $member->instrument ?? '-' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary rounded-circle p-2 text-white">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Cotisations</div>
                            <div class="h6 mb-0">
                                {{ $member ? number_format($member->contributions()->sum('amount'), 0, ',', ' ') : '0' }} FCFA
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-success rounded-circle p-2 text-white">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Événements</div>
                            <div class="h6 mb-0">{{ $member ? $member->eventParticipations()->count() : '0' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-info rounded-circle p-2 text-white">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Transactions</div>
                            <div class="h6 mb-0">{{ $member ? $member->transactions()->count() : '0' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu rapide -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-rocket me-2 text-primary"></i>
                    Accès rapide
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                      <div class="col-md-3 col-6">
        <a href="{{ route('my.card') }}" class="text-decoration-none">
            <div class="card bg-primary text-white text-center p-3">
                <i class="fas fa-id-card fa-2x mb-2"></i>
                <small>Ma carte</small>
            </div>
        </a>
    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('my.contributions') }}" class="text-decoration-none">
                            <div class="card bg-primary text-white text-center p-3">
                                <i class="fas fa-coins fa-2x mb-2"></i>
                                <small>Cotisations</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('my.transactions') }}" class="text-decoration-none">
                            <div class="card bg-success text-white text-center p-3">
                                <i class="fas fa-receipt fa-2x mb-2"></i>
                                <small>Transactions</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('my.events') }}" class="text-decoration-none">
                            <div class="card bg-info text-white text-center p-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <small>Événements</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                            <div class="card bg-warning text-white text-center p-3">
                                <i class="fas fa-tachometer-alt fa-2x mb-2"></i>
                                <small>Admin</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
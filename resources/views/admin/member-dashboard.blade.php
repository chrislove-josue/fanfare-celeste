@extends('layouts.admin')

@section('title', 'Mon espace - Dashboard')

@section('page-title', 'Mon espace')
@section('page-subtitle', 'Gérez votre profil et vos activités')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<style>
    /* ============================================
       STYLES PRO POUR L'ESPACE MEMBRE
    ============================================ */
    
    /* === CARTE DE PROFIL === */
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
    
    .profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }
    
    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        box-shadow: 0 8px 24px rgba(57, 70, 178, 0.35);
        transition: transform 0.3s ease;
    }
    
    .profile-card:hover .profile-avatar {
        transform: scale(1.05);
    }
    
    .profile-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-top: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .profile-email {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    
    .profile-email i {
        color: #3946B2;
        width: 16px;
    }
    
    /* === STATS CARDS === */
    .stat-card-pro {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .stat-card-pro::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--stat-color, #3946B2);
    }
    
    .stat-card-pro:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card-pro .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #fff;
        flex-shrink: 0;
        background: var(--stat-color, #3946B2);
        box-shadow: 0 4px 12px rgba(var(--stat-color-rgb, 57, 70, 178), 0.3);
    }
    
    .stat-card-pro .stat-number {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.2;
    }
    
    .stat-card-pro .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    /* === CARTE ÉVÉNEMENT === */
    .event-card-mini {
        background: #ffffff;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        border: 1px solid #f1f3f5;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .event-card-mini:hover {
        border-color: #3946B2;
        box-shadow: 0 4px 16px rgba(57, 70, 178, 0.1);
        transform: translateY(-2px);
    }
    
    .event-card-mini .event-date {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #3946B2;
        background: #eef2ff;
        padding: 2px 10px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    
    .event-card-mini .event-title {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 0.95rem;
        margin-bottom: 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .event-card-mini .event-location {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .event-card-mini .event-location i {
        color: #ef4444;
        width: 14px;
    }
    
    /* === CARTE ACCÈS RAPIDE === */
    .quick-access-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }
    
    .quick-access-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    .quick-btn {
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .quick-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    .quick-btn-primary {
        background: #3946B2;
        color: #fff;
    }
    
    .quick-btn-primary:hover {
        background: #2d3a9e;
        color: #fff;
    }
    
    .quick-btn-success {
        background: #22c55e;
        color: #fff;
    }
    
    .quick-btn-success:hover {
        background: #16a34a;
        color: #fff;
    }
    
    .quick-btn-info {
        background: #06b6d4;
        color: #fff;
    }
    
    .quick-btn-info:hover {
        background: #0891b2;
        color: #fff;
    }
    
    .quick-btn-warning {
        background: #f59e0b;
        color: #fff;
    }
    
    .quick-btn-warning:hover {
        background: #d97706;
        color: #fff;
    }
    
    /* === BADGES === */
    .badge-status {
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .badge-status.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .badge-status.pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-status.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    
    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 70px;
            height: 70px;
            font-size: 1.6rem;
        }
        .stat-card-pro .stat-number {
            font-size: 1.3rem;
        }
        .quick-btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
        .event-card-mini .event-title {
            font-size: 0.85rem;
        }
    }
</style>

<!-- ========================================== -->
<!-- PROFIL UTILISATEUR                         -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="profile-card">
            <!-- Avatar -->
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            
            <!-- Nom -->
            <h5 class="profile-name">{{ auth()->user()->name }}</h5>
            
            <!-- Email -->
            <p class="profile-email">
                <i class="fas fa-envelope me-1"></i> {{ auth()->user()->email }}
            </p>
            
            <!-- Badges -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                @if($member)
                    <span class="badge-status {{ $member->status === 'active' ? 'active' : 'pending' }}">
                        <i class="fas fa-{{ $member->status === 'active' ? 'check-circle' : 'clock' }} me-1"></i>
                        {{ ucfirst($member->status ?? 'Membre') }}
                    </span>
                    @if($member->section)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill" style="font-weight: 500; font-size: 0.75rem;">
                            <i class="fas fa-users me-1"></i> {{ $member->section }}
                        </span>
                    @endif
                    @if($member->instrument)
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill" style="font-weight: 500; font-size: 0.75rem;">
                            <i class="fas fa-music me-1"></i> {{ $member->instrument }}
                        </span>
                    @endif
                @else
                    <span class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i> Profil membre non complété
                    </span>
                @endif
            </div>
            
            <!-- Bouton modifier -->
            <a href="{{ route('my.profile') }}" class="btn btn-primary btn-sm px-4">
                <i class="fas fa-edit me-1"></i> Modifier mon profil
            </a>
            
            <!-- Séparateur -->
            <hr class="my-3">
            
            <!-- Infos supplémentaires -->
            <div class="row g-2 text-start">
                <div class="col-6">
                    <small class="text-muted d-block">Membre depuis</small>
                    <span class="fw-medium" style="font-size: 0.9rem;">
                        {{ $member ? $member->created_at->format('d/m/Y') : '-' }}
                    </span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">ID Membre</small>
                    <span class="fw-medium" style="font-size: 0.9rem;">
                        {{ $member ? $member->member_number : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========================================== -->
    <!-- STATISTIQUES MEMBRE                        -->
    <!-- ========================================== -->
    <div class="col-lg-8">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card-pro" style="--stat-color: #3946B2; --stat-color-rgb: 57, 70, 178;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-wrapper">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ number_format($myContributions, 0, ',', ' ') }}</div>
                            <div class="stat-label">Mes cotisations</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-wrapper">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ number_format($myTransactions) }}</div>
                            <div class="stat-label">Mes transactions</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card-pro" style="--stat-color: #8b5cf6; --stat-color-rgb: 139, 92, 246;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-wrapper">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <div class="stat-number">{{ number_format($myEvents) }}</div>
                            <div class="stat-label">Mes participations</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Évolution rapide -->
        <div class="mt-4">
            <div class="bg-light rounded-3 p-3 d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <span class="text-muted small">
                        <i class="fas fa-chart-simple me-1"></i> Activité récente
                    </span>
                    <span class="fw-medium ms-2" style="font-size: 0.9rem;">
                        Dernière connexion : {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Aujourd\'hui' }}
                    </span>
                </div>
                <div>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> En ligne
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ÉVÉNEMENTS À VENIR                        -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="quick-access-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0 fw-bold" style="font-size: 1rem;">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        Événements à venir
                    </h5>
                    <small class="text-muted">Ne manquez pas les prochains rendez-vous</small>
                </div>
                <span class="badge bg-primary px-3 py-2">
                    {{ $upcomingEvents->count() }} événement(s)
                </span>
            </div>
            
            @if($upcomingEvents->count() > 0)
                <div class="row g-3">
                    @foreach($upcomingEvents as $event)
                    <div class="col-md-6 col-lg-4">
                        <div class="event-card-mini">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="event-date">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $event->start_date->format('d/m/Y') }}
                                </span>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1" style="font-size: 0.6rem;">
                                    {{ $event->start_date->diffForHumans() }}
                                </span>
                            </div>
                            <h6 class="event-title">{{ $event->title }}</h6>
                            <p class="event-location mb-2">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $event->location ?? 'À définir' }}
                            </p>
                            <div class="mt-auto pt-2">
                                <a href="{{ route('public.events.show', $event) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-info-circle me-1"></i> Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <div class="bg-light rounded-3 p-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="fw-normal text-muted">Aucun événement à venir</h6>
                        <p class="text-muted small mb-0">Revenez bientôt pour découvrir les prochains événements.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ACCÈS RAPIDE                              -->
<!-- ========================================== -->
<div class="row g-4">
    <div class="col-12">
        <div class="quick-access-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="mb-0 fw-bold" style="font-size: 1rem;">
                        <i class="fas fa-rocket me-2 text-primary"></i>
                        Accès rapide
                    </h5>
                    <small class="text-muted">Retrouvez toutes vos informations en un clic</small>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('my.contributions') }}" class="quick-btn quick-btn-primary">
                        <i class="fas fa-coins"></i> Cotisations
                    </a>
                    <a href="{{ route('my.transactions') }}" class="quick-btn quick-btn-success">
                        <i class="fas fa-receipt"></i> Transactions
                    </a>
                    <a href="{{ route('my.events') }}" class="quick-btn quick-btn-info">
                        <i class="fas fa-calendar-check"></i> Événements
                    </a>
                    @if($member)
                        <a href="{{ route('my.card') }}" class="quick-btn quick-btn-warning">
                            <i class="fas fa-id-card"></i> Ma carte
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
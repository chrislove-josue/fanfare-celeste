@extends('layouts.admin')

@section('title', 'Mon espace - Dashboard')

@section('page-title', 'Mon espace')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-3">
                    <div class="avatar-circle bg-primary text-white" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 2rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>
                <h5 class="card-title">{{ auth()->user()->name }}</h5>
                <p class="text-muted small">
                    <i class="fas fa-envelope"></i> {{ auth()->user()->email }}
                </p>
                @if($member)
                    <p class="mb-0">
                        <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($member->status ?? 'Membre') }}
                        </span>
                        <span class="badge bg-secondary">{{ $member->section ?? 'Non défini' }}</span>
                    </p>
                @else
                    <p class="text-muted small">Profil membre non complété</p>
                @endif
                <div class="mt-3">
                    <a href="{{ route('my.profile') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Modifier mon profil
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card" style="border-left-color: #3946B2;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">{{ number_format($myContributions, 0, ',', ' ') }}</div>
                            <div class="stat-label">Mes cotisations</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3946B2, #6366f1);">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="border-left-color: #40A81B;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">{{ number_format($myTransactions) }}</div>
                            <div class="stat-label">Mes transactions</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #40A81B, #65c53c);">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" style="border-left-color: #8B5CF6;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-number">{{ number_format($myEvents) }}</div>
                            <div class="stat-label">Mes participations</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #a78bfa);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Événements à venir</h5>
            </div>
            <div class="card-body">
                @if($upcomingEvents->count() > 0)
                    <div class="row">
                        @foreach($upcomingEvents as $event)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $event->title }}</h6>
                                    <p class="text-muted small mb-1">
                                        <i class="fas fa-calendar-alt"></i> {{ $event->start_date->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-map-marker-alt"></i> {{ $event->location ?? 'À définir' }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('public.events.show', $event) }}" class="btn btn-sm btn-primary">
                                        Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Aucun événement à venir</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">Accès rapide</h5>
                        <p class="text-muted small mb-0">Gérez votre espace membre</p>
                    </div>
                    <div>
                        <a href="{{ route('my.contributions') }}" class="btn btn-primary">
                            <i class="fas fa-coins"></i> Mes cotisations
                        </a>
                        <a href="{{ route('my.transactions') }}" class="btn btn-success">
                            <i class="fas fa-receipt"></i> Mes transactions
                        </a>
                        <a href="{{ route('my.events') }}" class="btn btn-info">
                            <i class="fas fa-calendar-check"></i> Mes événements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
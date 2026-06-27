{{-- resources/views/admin/committee/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Gestion du Comité')

@section('page-title', 'Gestion du Comité')
@section('page-subtitle', 'Constituer et gérer le bureau exécutif de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Comité</li>
@endsection

@section('content')
<style>
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
    
    .position-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .position-badge.president { background: #fee2e2; color: #dc2626; }
    .position-badge.vice_president { background: #fef3c7; color: #d97706; }
    .position-badge.general_secretary { background: #dbeafe; color: #2563eb; }
    .position-badge.treasurer { background: #dcfce7; color: #16a34a; }
    .position-badge.conductor { background: #fce7f3; color: #db2777; }
    .position-badge.section_leader { background: #ede9fe; color: #7c3aed; }
    .position-badge.default { background: #e5e7eb; color: #6b7280; }
    
    .member-avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef2f6;
    }
    
    .member-avatar-placeholder-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        font-size: 0.75rem;
        text-decoration: none;
    }
    
    .btn-action.view { color: #3946B2; background: #eef2ff; border-color: #eef2ff; }
    .btn-action.view:hover { background: #3946B2; color: #fff; border-color: #3946B2; }
    
    .btn-action.edit { color: #f59e0b; background: #fef3c7; border-color: #fef3c7; }
    .btn-action.edit:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
    
    .btn-action.delete { color: #dc2626; background: #fee2e2; border-color: #fee2e2; }
    .btn-action.delete:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
    
    .btn-action.end { color: #8b5cf6; background: #ede9fe; border-color: #ede9fe; }
    .btn-action.end:hover { background: #8b5cf6; color: #fff; border-color: #8b5cf6; }
    
    .quick-actions-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .quick-actions-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    .quick-actions-card .quick-title h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .quick-actions-card .quick-title p {
        margin: 0;
        color: #6b7280;
        font-size: 0.8rem;
    }
    
    .quick-actions-card .quick-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .quick-actions-card .quick-buttons .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1.2rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .quick-actions-card .quick-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state h5 {
        color: #6b7280;
        font-weight: 500;
    }
    
    .empty-state p {
        color: #9ca3af;
    }
</style>

<!-- Statistiques -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #3946B2; --stat-color-rgb: 57, 70, 178;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-number">{{ $totalMembers ?? 0 }}</div>
                    <div class="stat-label">Membres disponibles</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper"><i class="fas fa-user-tie"></i></div>
                <div>
                    <div class="stat-number">{{ $committeeMembers->count() ?? 0 }}</div>
                    <div class="stat-label">Membres du comité</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #f59e0b; --stat-color-rgb: 245, 158, 11;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-number">{{ $committeeMembers->where('is_current', true)->count() ?? 0 }}</div>
                    <div class="stat-label">En fonction</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des membres -->
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-user-tie me-2 text-primary"></i>
            Bureau exécutif
            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 px-3 py-1">
                {{ $committeeMembers->count() }} membres
            </span>
        </h5>
        <a href="{{ route('admin.committee.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Ajouter un membre
        </a>
    </div>
    
    <div class="card-body p-0">
        @if($committeeMembers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 60px;">Photo</th>
                            <th>Nom</th>
                            <th>Poste</th>
                            <th>Début</th>
                            <th>Statut</th>
                            <th style="text-align: center; width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($committeeMembers as $index => $committee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($committee->member && $committee->member->photo)
                                    <img src="{{ asset('storage/' . $committee->member->photo) }}" 
                                         alt="{{ $committee->member->full_name }}" 
                                         class="member-avatar-sm">
                                @else
                                    <div class="member-avatar-placeholder-sm">
                                        {{ $committee->member ? strtoupper(substr($committee->member->first_name, 0, 1)) : '?' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $committee->member ? $committee->member->full_name : 'Membre inconnu' }}</div>
                                <small class="text-muted">{{ $committee->member ? $committee->member->member_number : '-' }}</small>
                            </td>
                            <td>
                                <span class="position-badge {{ $committee->position ?? 'default' }}">
                                    {{ $positions[$committee->position] ?? $committee->position }}
                                </span>
                            </td>
                            <td>{{ $committee->start_date ? $committee->start_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($committee->is_current)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> En fonction</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i> Terminé</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- ✅ BOUTON VOIR (SHOW) --}}
                                    <a href="{{ route('admin.committee.show', ['committee' => $committee->id]) }}" class="btn-action view" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    {{-- ✅ BOUTON MODIFIER --}}
                                    <a href="{{ route('admin.committee.edit', ['committee' => $committee->id]) }}" class="btn-action edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- ✅ BOUTON TERMINER LE MANDAT --}}
                                    @if($committee->is_current)
                                        <form action="{{ route('admin.committee.end', ['committee' => $committee->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action end" title="Terminer le mandat" onclick="return confirm('Terminer le mandat de ce membre ?')">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- ✅ BOUTON SUPPRIMER --}}
                                    <form action="{{ route('admin.committee.destroy', ['committee' => $committee->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Supprimer" onclick="return confirm('Supprimer ce membre du comité ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-user-tie"></i>
                <h5>Aucun membre dans le comité</h5>
                <p>Commencez par ajouter des membres au bureau exécutif.</p>
                <a href="{{ route('admin.committee.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-2"></i> Ajouter un membre
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Actions rapides -->
<div class="quick-actions-card">
    <div class="quick-title">
        <h5><i class="fas fa-bolt me-2 text-warning"></i> Actions rapides</h5>
        <p>Gérez le bureau exécutif efficacement</p>
    </div>
    <div class="quick-buttons">
        <a href="{{ route('admin.committee.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Ajouter un membre
        </a>
        <a href="#" class="btn btn-success" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Imprimer
        </a>
    </div>
</div>
@endsection
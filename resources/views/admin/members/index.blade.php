{{-- resources/views/admin/members/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Gestion des membres')

@section('page-title', 'Gestion des membres')
@section('page-subtitle', 'Gérez l\'ensemble des membres de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Membres</li>
@endsection

@section('content')
<style>
    /* ============================================
       STYLES PRO POUR LA GESTION DES MEMBRES
    ============================================ */
    
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
    
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    .table-card .card-header-custom {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .table-card .card-header-custom h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .table-card .card-header-custom h5 i {
        color: #3946B2;
    }
    
    .table-members {
        margin: 0;
        width: 100%;
        min-width: 700px;
    }
    
    .table-members thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 12px 16px;
        border-bottom: 2px solid #eef2f6;
        white-space: nowrap;
    }
    
    .table-members tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f3f5;
        color: #1a1a2e;
    }
    
    .table-members tbody tr:hover {
        background: #f8fafc;
    }
    
    .table-members .member-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef2f6;
    }
    
    .table-members .member-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3946B2, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-status {
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
    
    .badge-status.suspended {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-status.withdrawn {
        background: #e5e7eb;
        color: #6b7280;
    }
    
    .badge-status.deceased {
        background: #1f2937;
        color: #fff;
    }
    
    .badge-status.training {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .badge-user {
        padding: 2px 10px;
        border-radius: 10px;
        font-size: 0.6rem;
        font-weight: 600;
    }
    
    .badge-user.has-user {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .badge-user.no-user {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    
    .action-buttons .btn-action {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        font-size: 0.75rem;
        text-decoration: none;
        cursor: pointer;
    }
    
    .action-buttons .btn-action.view {
        color: #3946B2;
        background: #eef2ff;
        border-color: #eef2ff;
    }
    
    .action-buttons .btn-action.view:hover {
        background: #3946B2;
        color: #fff;
        border-color: #3946B2;
    }
    
    .action-buttons .btn-action.edit {
        color: #f59e0b;
        background: #fef3c7;
        border-color: #fef3c7;
    }
    
    .action-buttons .btn-action.edit:hover {
        background: #f59e0b;
        color: #fff;
        border-color: #f59e0b;
    }
    
    .action-buttons .btn-action.delete {
        color: #dc2626;
        background: #fee2e2;
        border-color: #fee2e2;
    }
    
    .action-buttons .btn-action.delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }
    
    .action-buttons .btn-action.roles {
        color: #8b5cf6;
        background: #ede9fe;
        border-color: #ede9fe;
    }
    
    .action-buttons .btn-action.roles:hover {
        background: #8b5cf6;
        color: #fff;
        border-color: #8b5cf6;
    }
    
    .action-buttons .btn-action.card {
        color: #06b6d4;
        background: #cffafe;
        border-color: #cffafe;
    }
    
    .action-buttons .btn-action.card:hover {
        background: #06b6d4;
        color: #fff;
        border-color: #06b6d4;
    }
    
    .action-buttons .btn-action.document {
        color: #8b5cf6;
        background: #ede9fe;
        border-color: #ede9fe;
    }
    
    .action-buttons .btn-action.document:hover {
        background: #8b5cf6;
        color: #fff;
        border-color: #8b5cf6;
    }
    
    .action-buttons .btn-action.document-disabled {
        color: #9ca3af;
        background: #f3f4f6;
        border-color: #f3f4f6;
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .action-buttons .btn-action.approve {
        color: #22c55e;
        background: #dcfce7;
        border-color: #dcfce7;
    }
    
    .action-buttons .btn-action.approve:hover {
        background: #22c55e;
        color: #fff;
        border-color: #22c55e;
    }
    
    .filter-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #eef2f6;
    }
    
    .filter-section .form-control,
    .filter-section .form-select {
        border-radius: 8px;
        font-size: 0.85rem;
        border-color: #e5e7eb;
    }
    
    .filter-section .form-control:focus,
    .filter-section .form-select:focus {
        border-color: #3946B2;
        box-shadow: 0 0 0 3px rgba(57, 70, 178, 0.1);
    }
    
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
    
    /* Styles pour le modal du document */
    #documentModal .modal-content {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    #documentModal .modal-header {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
    }

    #documentModal .modal-body {
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 1.5rem;
    }

    #documentModal .modal-body img {
        max-height: 500px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    #documentModal .modal-body iframe {
        width: 100%;
        height: 500px;
        border: none;
        border-radius: 8px;
    }

    #documentModal .modal-footer {
        background: #fff;
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .stat-card-pro .stat-number {
            font-size: 1.3rem;
        }
        .stat-card-pro .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .quick-actions-card {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .quick-actions-card .quick-buttons {
            justify-content: center;
        }
        .table-members {
            min-width: 500px;
        }
        .filter-section .row {
            gap: 0.5rem;
        }
        #documentModal .modal-body {
            min-height: 250px;
            padding: 1rem;
        }
        #documentModal .modal-body iframe {
            height: 300px;
        }
        #documentModal .modal-body img {
            max-height: 300px;
        }
    }
</style>

<!-- ========================================== -->
<!-- STATISTIQUES                                -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #3946B2; --stat-color-rgb: 57, 70, 178;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalMembers) }}</div>
                    <div class="stat-label">Total Membres</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($activeMembers) }}</div>
                    <div class="stat-label">Membres Actifs</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #f59e0b; --stat-color-rgb: 245, 158, 11;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($pendingMembers) }}</div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #8b5cf6; --stat-color-rgb: 139, 92, 246;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($newMembers) }}</div>
                    <div class="stat-label">Nouveaux ce mois</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- FILTRES                                    -->
<!-- ========================================== -->
<div class="filter-section">
    <form method="GET" action="{{ route('admin.members.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3 col-lg-2">
            <label class="form-label fw-semibold small">Recherche</label>
            <input type="text" name="search" class="form-control" placeholder="Nom, email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 col-lg-1_5">
            <label class="form-label fw-semibold small">Statut</label>
            <select name="status" class="form-select">
                <option value="">Tous</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-lg-1_5">
            <label class="form-label fw-semibold small">Section</label>
            <select name="section" class="form-select">
                <option value="">Toutes</option>
                @foreach($sections as $section)
                    <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                        {{ $section }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-lg-1_5">
            <label class="form-label fw-semibold small">Instrument</label>
            <select name="instrument" class="form-select">
                <option value="">Tous</option>
                @foreach($instruments as $instrument)
                    <option value="{{ $instrument }}" {{ request('instrument') == $instrument ? 'selected' : '' }}>
                        {{ $instrument }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-lg-1_5">
            <label class="form-label fw-semibold small">Compte</label>
            <select name="has_user" class="form-select">
                <option value="">Tous</option>
                <option value="yes" {{ request('has_user') == 'yes' ? 'selected' : '' }}>Avec compte</option>
                <option value="no" {{ request('has_user') == 'no' ? 'selected' : '' }}>Sans compte</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="col-md-auto">
            @if(request()->anyFilled(['search', 'status', 'section', 'instrument', 'has_user']))
                <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            @endif
        </div>
    </form>
</div>

<!-- ========================================== -->
<!-- TABLEAU DES MEMBRES                        -->
<!-- ========================================== -->
<div class="table-card">
    <div class="card-header-custom">
        <h5>
            <i class="fas fa-list-ul"></i>
            Liste des membres
            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 px-3 py-1" style="font-weight: 500; font-size: 0.7rem;">
                {{ $members->total() }} membres
            </span>
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.members.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-export me-1"></i> Exporter
            </a>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nouveau membre
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-members">
            <thead>
                <tr>
                    <th style="width: 50px;">N°</th>
                    <th style="width: 50px;">Photo</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Instrument</th>
                    <th>Section</th>
                    <th>Statut</th>
                    <th>Compte</th>
                    <th style="width: 250px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>
                        <span class="fw-semibold small">{{ $member->member_number }}</span>
                    </td>
                    <td>
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" 
                                 alt="{{ $member->full_name }}" 
                                 class="member-avatar">
                        @else
                            <div class="member-avatar-placeholder">
                                {{ strtoupper(substr($member->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-medium">{{ $member->full_name }}</span>
                    </td>
                    <td>{{ $member->email ?? '-' }}</td>
                    <td>{{ $member->phone ?? '-' }}</td>
                    <td>{{ $member->instrument ?? '-' }}</td>
                    <td>{{ $member->section ?? '-' }}</td>
                    <td>
                        <span class="badge-status {{ $member->status }}">
                            <i class="fas fa-{{ $member->status === 'active' ? 'check-circle' : ($member->status === 'pending' ? 'clock' : ($member->status === 'training' ? 'graduation-cap' : 'times-circle')) }}"></i>
                            {{ ucfirst($member->status) }}
                        </span>
                    </td>
                    <td>
                        @if($member->user)
                            <span class="badge-user has-user">
                                <i class="fas fa-check-circle me-1"></i> Oui
                            </span>
                        @else
                            <span class="badge-user no-user">
                                <i class="fas fa-times-circle me-1"></i> Non
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons justify-content-center">
                            <a href="{{ route('admin.members.show', $member) }}" class="btn-action view" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.members.edit', $member) }}" class="btn-action edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- ===== BOUTON DOCUMENT D'IDENTITÉ (MODAL) ===== -->
                            @if($member->id_document)
                                @php
                                    $fileName = basename($member->id_document);
                                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                    $documentPath = asset('storage/' . $member->id_document);
                                @endphp
                                <button type="button" 
                                        class="btn-action document" 
                                        title="Voir le document d'identité"
                                        onclick="viewDocumentModal('{{ $documentPath }}', '{{ $member->full_name }}', '{{ $member->member_number }}', '{{ $fileName }}')">
                                    <i class="fas fa-id-card"></i>
                                </button>
                            @else
                                <span class="btn-action document-disabled" title="Aucun document">
                                    <i class="fas fa-id-card"></i>
                                </span>
                            @endif
                            
                            @if($member->user)
                                <a href="{{ route('admin.members.roles', $member) }}" class="btn-action roles" title="Gérer les rôles">
                                    <i class="fas fa-shield-alt"></i>
                                </a>
                            @endif
                            <a href="{{ route('admin.members.card', $member) }}" class="btn-action card" title="Carte membre" target="_blank">
                                <i class="fas fa-id-card"></i>
                            </a>
                            @if($member->status === 'pending')
                                <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-action approve" title="Approuver" onclick="return confirm('Approuver ce membre ?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            @can('delete members')
                            <button type="button" class="btn-action delete" title="Supprimer" 
                                    onclick="confirmDelete('{{ $member->id }}', '{{ $member->full_name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-5 text-muted">
                        <i class="fas fa-users fa-2x d-block mb-2"></i>
                        <p class="mb-0">Aucun membre trouvé</p>
                        <a href="{{ route('admin.members.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Ajouter un membre
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent border-0 py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                Affichage de {{ $members->firstItem() ?? 0 }} à {{ $members->lastItem() ?? 0 }} sur {{ $members->total() }} membres
            </span>
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ACTIONS RAPIDES                            -->
<!-- ========================================== -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="quick-actions-card">
            <div class="quick-title">
                <h5>
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Actions rapides
                </h5>
                <p>Gérez les membres efficacement en un clic</p>
            </div>
            <div class="quick-buttons">
                <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Ajouter un membre
                </a>
                <a href="{{ route('admin.members.export') }}" class="btn btn-success">
                    <i class="fas fa-file-export me-1"></i> Exporter PDF
                </a>
                @if($pendingMembers > 0)
                    <a href="{{ route('admin.members.index', ['status' => 'pending']) }}" class="btn btn-warning">
                        <i class="fas fa-clock me-1"></i> En attente
                        <span class="badge bg-dark text-white ms-1">{{ $pendingMembers }}</span>
                    </a>
                @endif
                @if(($usersCount ?? 0) < $totalMembers)
                    <a href="{{ route('admin.members.index', ['has_user' => 'no']) }}" class="btn btn-info text-white">
                        <i class="fas fa-user-plus me-1"></i> Sans compte
                        <span class="badge bg-dark text-white ms-1">{{ $totalMembers - ($usersCount ?? 0) }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE CONFIRMATION SUPPRESSION          -->
<!-- ========================================== -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le membre <strong id="deleteMemberName"></strong> ?</p>
                <p class="text-muted small">Cette action est irréversible. Toutes les données liées à ce membre seront supprimées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POUR LE DOCUMENT                     -->
<!-- ========================================== -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-id-card text-primary me-2"></i>
                    Document d'identité - <span id="documentModalMemberName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="documentModalBody">
                <div class="text-center" id="documentLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement du document...</p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex flex-wrap gap-3 me-auto">
                    <span class="text-muted small">
                        <i class="fas fa-user me-1"></i>
                        <span id="documentModalMemberInfo"></span>
                    </span>
                    <span class="text-muted small">
                        <i class="fas fa-hashtag me-1"></i>
                        <span id="documentModalMemberNumber"></span>
                    </span>
                </div>
                <div class="d-flex gap-2">
                    <a href="#" id="documentDownloadLink" class="btn btn-primary btn-sm" download>
                        <i class="fas fa-download me-1"></i> Télécharger
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteMemberName').textContent = name;
        document.getElementById('deleteForm').action = '{{ route("admin.members.index") }}/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // ==========================================
    // GESTION DU DOCUMENT DANS LE MODAL
    // ==========================================

    function viewDocumentModal(documentPath, memberName, memberNumber, fileName) {
        // Mettre à jour les informations du modal
        document.getElementById('documentModalMemberName').textContent = memberName;
        document.getElementById('documentModalMemberInfo').textContent = memberName;
        document.getElementById('documentModalMemberNumber').textContent = memberNumber;
        document.getElementById('documentDownloadLink').href = documentPath;
        document.getElementById('documentDownloadLink').download = 'document_' + memberNumber + '.' + fileName.split('.').pop();
        
        // Afficher le loader
        const body = document.getElementById('documentModalBody');
        body.innerHTML = `
            <div class="text-center" id="documentLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement du document...</p>
            </div>
        `;
        
        // Ouvrir le modal
        const modal = new bootstrap.Modal(document.getElementById('documentModal'));
        modal.show();
        
        // Déterminer le type de fichier
        const extension = fileName.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension);
        
        if (isImage) {
            // Charger l'image
            const img = new Image();
            img.onload = function() {
                body.innerHTML = `
                    <img src="${documentPath}" 
                         alt="Document d'identité" 
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 500px; max-width: 100%; object-fit: contain;">
                `;
            };
            img.onerror = function() {
                body.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p class="text-muted">Impossible de charger l'image</p>
                        <a href="${documentPath}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Ouvrir dans un nouvel onglet
                        </a>
                    </div>
                `;
            };
            img.src = documentPath;
        } else if (extension === 'pdf') {
            // Afficher le PDF dans un iframe
            body.innerHTML = `
                <iframe src="${documentPath}" 
                        style="width: 100%; height: 500px; border: none; border-radius: 8px;" 
                        frameborder="0"></iframe>
            `;
        } else {
            // Autres types de fichiers
            body.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-primary mb-3"></i>
                    <p class="text-muted">Type de fichier non prévisualisable</p>
                    <a href="${documentPath}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i> Ouvrir le fichier
                    </a>
                </div>
            `;
        }
    }

    // Ouvrir automatiquement le modal si on vient avec un paramètre
    document.addEventListener('DOMContentLoaded', function() {
        @if(request()->has('modal'))
            setTimeout(function() {
                const memberId = '{{ request('member_id') }}';
                if (memberId) {
                    // Trouver le bouton du document pour ce membre
                    const buttons = document.querySelectorAll('.btn-action.document');
                    buttons.forEach(function(btn) {
                        if (btn.getAttribute('data-member-id') === memberId) {
                            btn.click();
                        }
                    });
                }
            }, 500);
        @endif
    });
</script>
@endpush
@endsection
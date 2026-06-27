{{-- resources/views/admin/members/roles.blade.php --}}

@extends('layouts.admin')

@section('title', 'Gestion des rôles - ' . $member->full_name)

@section('page-title', 'Gestion des rôles')
@section('page-subtitle', $member->full_name . ' - ' . $member->member_number)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.members.show', $member) }}">{{ $member->full_name }}</a></li>
    <li class="breadcrumb-item active">Rôles</li>
@endsection

@section('content')
<style>
    .role-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #f1f3f5;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
        position: relative;
    }
    
    .role-card:hover {
        border-color: #3946B2;
        box-shadow: 0 4px 16px rgba(57, 70, 178, 0.1);
        transform: translateY(-2px);
    }
    
    .role-card.selected {
        border-color: #3946B2;
        background: #eef2ff;
        box-shadow: 0 4px 16px rgba(57, 70, 178, 0.15);
    }
    
    .role-card .role-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #fff;
        margin-bottom: 0.75rem;
    }
    
    .role-card .role-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
    }
    
    .role-card .role-description {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .role-card .role-check {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #3946B2;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s ease;
    }
    
    .role-card.selected .role-check {
        opacity: 1;
        transform: scale(1);
    }
    
    .current-role-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .current-role-badge.admin {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .current-role-badge.manager {
        background: #fef3c7;
        color: #d97706;
    }
    
    .current-role-badge.member {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .current-role-badge.finance {
        background: #dbeafe;
        color: #2563eb;
    }
</style>

<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- ===== COLONNE GAUCHE : PROFIL ===== -->
        <div class="col-lg-4">
            <div class="profile-card" style="background: #ffffff; border-radius: 20px; padding: 2rem 1.5rem; text-align: center; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.04);">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" 
                         class="rounded-circle" 
                         width="100" height="100" 
                         style="object-fit: cover; border: 4px solid #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.15);"
                         alt="{{ $member->full_name }}">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: 700; border: 4px solid #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                    </div>
                @endif
                
                <h4 class="mt-3 mb-1">{{ $member->full_name }}</h4>
                <p class="text-muted small">{{ $member->member_number }}</p>
                
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                    <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'warning' }}">
                        {{ ucfirst($member->status) }}
                    </span>
                    @if($member->section)
                        <span class="badge bg-secondary">{{ $member->section }}</span>
                    @endif
                </div>
                
                <!-- Rôles actuels -->
                <div class="mt-3">
                    <label class="text-muted small fw-semibold d-block mb-2">Rôles actuels</label>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @forelse($member->user?->roles ?? [] as $role)
                            <span class="current-role-badge {{ $role->name }}">
                                <i class="fas fa-shield-alt"></i>
                                {{ ucfirst($role->name) }}
                            </span>
                        @empty
                            <span class="text-muted small">Aucun rôle assigné</span>
                        @endforelse
                    </div>
                </div>
                
                <div class="mt-3 d-flex gap-2 justify-content-center flex-wrap">
                    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== COLONNE DROITE : GESTION DES RÔLES ===== -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2 text-primary"></i>
                        Gestion des rôles
                    </h5>
                    <small class="text-muted">Assignez ou retirez des rôles à ce membre</small>
                </div>
                <div class="card-body">
                    <form id="rolesForm" action="{{ route('admin.members.sync-roles', $member) }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            @foreach($availableRoles as $role)
                            <div class="col-md-6 col-lg-4">
                                <div class="role-card {{ in_array($role->name, $userRoles) ? 'selected' : '' }}" 
                                     onclick="toggleRole('{{ $role->name }}')">
                                    <div class="role-check">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="role-icon" style="background: {{ $role->name === 'super-admin' ? '#dc2626' : ($role->name === 'admin' ? '#3946B2' : ($role->name === 'manager' ? '#f59e0b' : ($role->name === 'finance' ? '#2563eb' : '#22c55e'))) }};">
                                        <i class="fas fa-{{ $role->name === 'super-admin' ? 'crown' : ($role->name === 'admin' ? 'user-shield' : ($role->name === 'manager' ? 'users-cog' : ($role->name === 'finance' ? 'coins' : 'user'))) }}"></i>
                                    </div>
                                    <div class="role-name">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</div>
                                    <div class="role-description">
                                        @php
                                            $descriptions = [
                                                'super-admin' => 'Accès total à toutes les fonctionnalités',
                                                'admin' => 'Gestion complète du site et des utilisateurs',
                                                'manager' => 'Gestion des membres et des activités',
                                                'finance' => 'Gestion financière et des transactions',
                                                'member' => 'Accès à l\'espace membre'
                                            ];
                                        @endphp
                                        {{ $descriptions[$role->name] ?? 'Rôle utilisateur standard' }}
                                    </div>
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                           id="role-{{ $role->name }}" 
                                           {{ in_array($role->name, $userRoles) ? 'checked' : '' }}
                                           style="display: none;">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Cliquez sur une carte pour assigner ou retirer un rôle
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Sauvegarder les rôles
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ===== TABLEAU DES PERMISSIONS ===== -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-check me-2 text-success"></i>
                        Permissions du membre
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Permission</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $permissions = [
                                        'view members' => 'Voir les membres',
                                        'create members' => 'Créer des membres',
                                        'edit members' => 'Modifier des membres',
                                        'delete members' => 'Supprimer des membres',
                                        'approve members' => 'Approuver des membres',
                                        'view finances' => 'Voir les finances',
                                        'create finances' => 'Créer des transactions',
                                        'edit finances' => 'Modifier des transactions',
                                        'delete finances' => 'Supprimer des transactions',
                                        'view events' => 'Voir les événements',
                                        'create events' => 'Créer des événements',
                                        'edit events' => 'Modifier des événements',
                                        'delete events' => 'Supprimer des événements',
                                    ];
                                    $userPermissions = $member->user?->getAllPermissions()->pluck('name')->toArray() ?? [];
                                @endphp
                                @foreach($permissions as $key => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>
                                        @if(in_array($key, $userPermissions))
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Autorisé
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle me-1"></i> Non autorisé
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRole(roleName) {
        const checkbox = document.getElementById('role-' + roleName);
        const card = checkbox.closest('.role-card');
        
        checkbox.checked = !checkbox.checked;
        card.classList.toggle('selected');
    }

    // Assurer la soumission du formulaire
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('rolesForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Vérifier qu'au moins un rôle est sélectionné
                const checkboxes = form.querySelectorAll('input[name="roles[]"]:checked');
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    alert('Veuillez sélectionner au moins un rôle.');
                }
            });
        }
    });
</script>
@endpush
@endsection
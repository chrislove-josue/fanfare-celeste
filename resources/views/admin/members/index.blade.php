@extends('layouts.admin')

@section('title', 'Gestion des membres')

@section('page-title', 'Gestion des membres')
@section('page-subtitle', 'Gérer les membres de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item active">Membres</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2 text-primary"></i>
            Gestion des membres
        </h1>
        <div>
            <a href="{{ route('admin.members.export') }}" class="btn btn-success me-2">
                <i class="fas fa-file-export me-2"></i> Exporter PDF
            </a>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Nouveau membre
            </a>
        </div>
    </div>

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

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2 text-white">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total membres</div>
                        <div class="h5 mb-0">{{ $totalMembers ?? \App\Models\Member::count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle p-2 text-white">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Membres actifs</div>
                        <div class="h5 mb-0">{{ $activeMembers ?? \App\Models\Member::where('status', 'active')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-warning rounded-circle p-2 text-white">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">En attente</div>
                        <div class="h5 mb-0">{{ $pendingMembers ?? \App\Models\Member::where('status', 'pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-info rounded-circle p-2 text-white">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Avec compte</div>
                        <div class="h5 mb-0">{{ $usersCount ?? \App\Models\Member::whereHas('user')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.members.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search me-1"></i> Recherche
                    </label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nom, prénom...">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Section</label>
                    <select name="section" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                {{ $section }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Compte utilisateur</label>
                    <select name="has_user" class="form-select">
                        <option value="">Tous</option>
                        <option value="yes" {{ request('has_user') == 'yes' ? 'selected' : '' }}>Avec compte</option>
                        <option value="no" {{ request('has_user') == 'no' ? 'selected' : '' }}>Sans compte</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Instrument</label>
                    <select name="instrument" class="form-select">
                        <option value="">Tous</option>
                        @foreach($instruments as $instrument)
                            <option value="{{ $instrument }}" {{ request('instrument') == $instrument ? 'selected' : '' }}>
                                {{ $instrument }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary" title="Réinitialiser">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des membres -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">N°</th>
                            <th style="width: 60px;">Photo</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Instrument</th>
                            <th>Section</th>
                            <th>Statut</th>
                            <th>Compte</th>
                            <th style="width: 230px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $member->member_number }}</span>
                            </td>
                            <td>
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" 
                                         alt="{{ $member->full_name }}" 
                                         class="rounded-circle" 
                                         width="40" height="40" 
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; font-size: 14px; font-weight: 600;">
                                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $member->full_name }}</strong>
                            </td>
                            <td>{{ $member->email ?? '-' }}</td>
                            <td>{{ $member->phone ?? '-' }}</td>
                            <td>{{ $member->instrument ?? '-' }}</td>
                            <td>{{ $member->section ?? '-' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'inactive' => 'secondary',
                                        'suspended' => 'danger',
                                        'withdrawn' => 'secondary',
                                        'deceased' => 'dark',
                                        'training' => 'info'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$member->status] ?? 'secondary' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                @if($member->user)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Oui
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times-circle me-1"></i> Non
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Voir -->
                                    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Modifier -->
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Carte -->
                                    <a href="{{ route('admin.members.card', $member) }}" class="btn btn-primary" title="Carte">
                                        <i class="fas fa-id-card"></i>
                                    </a>

                                    <!-- Approuver (si en attente) -->
                                    @if($member->status === 'pending')
                                        <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Approuver" onclick="return confirm('Approuver ce membre ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Créer un compte / Réinitialiser -->
                                    @if(!$member->user)
                                        <button type="button" class="btn btn-success" title="Créer un compte" 
                                                data-bs-toggle="modal" data-bs-target="#createUserModal{{ $member->id }}">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary" title="Réinitialiser mot de passe"
                                                data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $member->id }}">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    @endif

                                    <!-- Supprimer -->
                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Supprimer définitivement ce membre ? Cette action est irréversible.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Créer un compte utilisateur -->
                                <div class="modal fade" id="createUserModal{{ $member->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.members.create-user', $member) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-user-plus me-2 text-success"></i>
                                                        Créer un compte pour {{ $member->full_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Vous allez créer un compte utilisateur pour <strong>{{ $member->full_name }}</strong>.
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Email de connexion</label>
                                                        <input type="email" class="form-control" value="{{ $member->email }}" readonly>
                                                        <small class="text-muted">L'email est utilisé comme identifiant</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Mot de passe *</label>
                                                        <div class="input-group">
                                                            <input type="text" name="password" id="password{{ $member->id }}" 
                                                                   class="form-control" value="{{ Str::random(10) }}" required>
                                                            <button type="button" class="btn btn-outline-secondary" 
                                                                    onclick="generatePassword({{ $member->id }})">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-secondary" 
                                                                    onclick="togglePasswordVisibility({{ $member->id }})">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">Le membre utilisera ce mot de passe pour se connecter</small>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        <strong>Informations à transmettre au membre :</strong>
                                                        <ul class="mb-0 mt-2">
                                                            <li>🔗 Lien de connexion : <strong>{{ url('/login') }}</strong></li>
                                                            <li>📧 Email : <strong>{{ $member->email }}</strong></li>
                                                            <li>🔑 Mot de passe : <strong id="passwordDisplay{{ $member->id }}">••••••••</strong></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-save me-2"></i> Créer le compte
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Réinitialiser mot de passe -->
                                <div class="modal fade" id="resetPasswordModal{{ $member->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.members.reset-password', $member) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-key me-2 text-warning"></i>
                                                        Réinitialiser le mot de passe de {{ $member->full_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Vous allez réinitialiser le mot de passe de <strong>{{ $member->full_name }}</strong>.
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Nouveau mot de passe *</label>
                                                        <div class="input-group">
                                                            <input type="text" name="password" id="resetPassword{{ $member->id }}" 
                                                                   class="form-control" value="{{ Str::random(10) }}" required>
                                                            <button type="button" class="btn btn-outline-secondary" 
                                                                    onclick="generateResetPassword({{ $member->id }})">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-secondary" 
                                                                    onclick="toggleResetPasswordVisibility({{ $member->id }})">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">Le membre utilisera ce nouveau mot de passe</small>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        <strong>Nouveau mot de passe :</strong>
                                                        <span id="resetPasswordDisplay{{ $member->id }}">••••••••</span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-save me-2"></i> Réinitialiser
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-users fa-2x d-block mb-2 text-muted"></i>
                                <p class="text-muted">Aucun membre trouvé</p>
                                <a href="{{ route('admin.members.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i> Ajouter un membre
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small">
                        Affichage de {{ $members->firstItem() ?? 0 }} à {{ $members->lastItem() ?? 0 }} sur {{ $members->total() }} membres
                    </span>
                </div>
                <div>
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generatePassword(memberId) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('password' + memberId).value = password;
        document.getElementById('passwordDisplay' + memberId).textContent = password;
    }

    function generateResetPassword(memberId) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('resetPassword' + memberId).value = password;
        document.getElementById('resetPasswordDisplay' + memberId).textContent = password;
    }

    function togglePasswordVisibility(memberId) {
        const input = document.getElementById('password' + memberId);
        const display = document.getElementById('passwordDisplay' + memberId);
        if (input.type === 'password') {
            input.type = 'text';
            display.textContent = input.value;
        } else {
            input.type = 'password';
            display.textContent = '••••••••';
        }
    }

    function toggleResetPasswordVisibility(memberId) {
        const input = document.getElementById('resetPassword' + memberId);
        const display = document.getElementById('resetPasswordDisplay' + memberId);
        if (input.type === 'password') {
            input.type = 'text';
            display.textContent = input.value;
        } else {
            input.type = 'password';
            display.textContent = '••••••••';
        }
    }

    // Afficher le mot de passe généré automatiquement dans les modales
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="createUserModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const id = this.id.replace('createUserModal', '');
                const passwordField = document.getElementById('password' + id);
                if (passwordField) {
                    document.getElementById('passwordDisplay' + id).textContent = passwordField.value;
                }
            });
        });

        document.querySelectorAll('[id^="resetPasswordModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const id = this.id.replace('resetPasswordModal', '');
                const passwordField = document.getElementById('resetPassword' + id);
                if (passwordField) {
                    document.getElementById('resetPasswordDisplay' + id).textContent = passwordField.value;
                }
            });
        });
    });
</script>
@endpush
@endsection
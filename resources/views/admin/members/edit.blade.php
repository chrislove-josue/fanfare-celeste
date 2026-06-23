@extends('layouts.admin')

@section('title', 'Modifier le membre')

@section('page-title', 'Modifier le membre')
@section('page-subtitle', 'Modifier les informations du membre')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Membres</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier le membre
        </h1>
        <div>
            <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('admin.members.card', $member) }}" class="btn btn-primary">
                <i class="fas fa-id-card me-2"></i> Carte
            </a>
        </div>
    </div>

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

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.members.update', $member) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Photo -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Photo</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format : JPG, PNG | Taille max : 2MB</small>
                        @if($member->photo_url)
                            <div class="mt-2">
                                <img src="{{ $member->photo_url }}" 
                                     class="rounded-circle" 
                                     width="100" height="100" 
                                     style="object-fit: cover; border: 3px solid #3946B2;" 
                                     alt="Photo membre">
                            </div>
                        @endif
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Numéro de membre -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">N° Membre</label>
                        <input type="text" class="form-control bg-light" value="{{ $member->member_number }}" disabled readonly>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Statut *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $member->status) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prénom -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prénom *</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name', $member->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nom *</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name', $member->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email (lecture seule) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $member->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Téléphone *</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $member->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- WhatsApp -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                               value="{{ old('whatsapp', $member->whatsapp) }}">
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Section</label>
                        <select name="section" class="form-select @error('section') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($sections as $key => $label)
                                <option value="{{ $key }}" {{ old('section', $member->section) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rôle dans la section -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rôle dans la section</label>
                        <input type="text" name="section_role" class="form-control @error('section_role') is-invalid @enderror" 
                               value="{{ old('section_role', $member->section_role) }}" placeholder="Responsable, Membre...">
                        @error('section_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Instrument -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Instrument</label>
                        <select name="instrument" class="form-select @error('instrument') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($instruments as $key => $label)
                                <option value="{{ $key }}" {{ old('instrument', $member->instrument) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('instrument')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveau instrument -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Niveau instrument</label>
                        <select name="instrument_level" class="form-select @error('instrument_level') is-invalid @enderror">
                            <option value="">-- Sélectionner --</option>
                            @foreach($instrumentLevels as $key => $label)
                                <option value="{{ $key }}" {{ old('instrument_level', $member->instrument_level) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('instrument_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date d'entrée -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date d'entrée</label>
                        <input type="date" name="entry_date" class="form-control @error('entry_date') is-invalid @enderror" 
                               value="{{ old('entry_date', optional($member->entry_date)->format('Y-m-d')) }}">
                        @error('entry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Adresse</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $member->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date de naissance -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date de naissance</label>
                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                               value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lieu de naissance -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Lieu de naissance</label>
                        <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" 
                               value="{{ old('birth_place', $member->birth_place) }}">
                        @error('birth_place')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact d'urgence (Nom)</label>
                        <input type="text" name="emergency_contact_name" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                               value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                        @error('emergency_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact d'urgence (Téléphone)</label>
                        <input type="text" name="emergency_contact_phone" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                               value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                        @error('emergency_contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes médicales -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Notes médicales</label>
                        <textarea name="medical_notes" class="form-control @error('medical_notes') is-invalid @enderror" rows="2">{{ old('medical_notes', $member->medical_notes) }}</textarea>
                        @error('medical_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Compétences -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Compétences</label>
                        <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror" 
                               value="{{ old('skills', $member->skills) }}" placeholder="Séparées par des virgules">
                        @error('skills')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes admin -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Notes (Admin)</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $member->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ===== COMPTE UTILISATEUR ===== -->
<div class="col-md-12 mt-3">
    <h6 class="border-bottom pb-2">
        <i class="fas fa-user me-2 text-primary"></i>
        Compte utilisateur
    </h6>
    <div class="d-flex align-items-center gap-3 flex-wrap">
        @if($member->user)
            <span class="badge bg-success">
                <i class="fas fa-check-circle me-1"></i> 
                Lié à : {{ $member->user->email }}
            </span>
            <span class="text-muted small">
                (Utilisateur ID: #{{ $member->user->id }})
            </span>
            <button type="button" class="btn btn-warning btn-sm" 
                    data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $member->id }}">
                <i class="fas fa-key me-1"></i> Réinitialiser
            </button>
            <a href="{{ route('my.card') }}" target="_blank" class="btn btn-info btn-sm">
                <i class="fas fa-id-card me-1"></i> Voir carte
            </a>
        @else
            <span class="badge bg-secondary">
                <i class="fas fa-times-circle me-1"></i> Aucun compte utilisateur
            </span>
            <button type="button" class="btn btn-success btn-sm" 
                    data-bs-toggle="modal" data-bs-target="#createUserModal{{ $member->id }}">
                <i class="fas fa-user-plus me-1"></i> Créer un compte
            </button>
        @endif
    </div>
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
                            <input type="text" name="password" id="editPassword{{ $member->id }}" 
                                   class="form-control" value="{{ Str::random(10) }}" required>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="generateEditPassword({{ $member->id }})">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="toggleEditPasswordVisibility({{ $member->id }})">
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
                            <li>🔑 Mot de passe : <strong id="editPasswordDisplay{{ $member->id }}">••••••••</strong></li>
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
                            <input type="text" name="password" id="editResetPassword{{ $member->id }}" 
                                   class="form-control" value="{{ Str::random(10) }}" required>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="generateEditResetPassword({{ $member->id }})">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="toggleEditResetPasswordVisibility({{ $member->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Le membre utilisera ce nouveau mot de passe</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nouveau mot de passe :</strong>
                        <span id="editResetPasswordDisplay{{ $member->id }}">••••••••</span>
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

                    <!-- Informations système -->
                    <div class="col-md-12 mt-3">
                        <hr>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="fw-semibold">Créé le</label>
                                <p class="mb-0 text-muted">{{ $member->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Dernière modification</label>
                                <p class="mb-0 text-muted">{{ $member->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">ID</label>
                                <p class="mb-0 text-muted">#{{ $member->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generateEditPassword(memberId) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('editPassword' + memberId).value = password;
        document.getElementById('editPasswordDisplay' + memberId).textContent = password;
    }

    function generateEditResetPassword(memberId) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('editResetPassword' + memberId).value = password;
        document.getElementById('editResetPasswordDisplay' + memberId).textContent = password;
    }

    function toggleEditPasswordVisibility(memberId) {
        const input = document.getElementById('editPassword' + memberId);
        const display = document.getElementById('editPasswordDisplay' + memberId);
        if (input.type === 'password') {
            input.type = 'text';
            display.textContent = input.value;
        } else {
            input.type = 'password';
            display.textContent = '••••••••';
        }
    }

    function toggleEditResetPasswordVisibility(memberId) {
        const input = document.getElementById('editResetPassword' + memberId);
        const display = document.getElementById('editResetPasswordDisplay' + memberId);
        if (input.type === 'password') {
            input.type = 'text';
            display.textContent = input.value;
        } else {
            input.type = 'password';
            display.textContent = '••••••••';
        }
    }

    // Afficher les mots de passe générés automatiquement dans les modales
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="createUserModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const id = this.id.replace('createUserModal', '');
                const passwordField = document.getElementById('editPassword' + id);
                if (passwordField) {
                    document.getElementById('editPasswordDisplay' + id).textContent = passwordField.value;
                }
            });
        });

        document.querySelectorAll('[id^="resetPasswordModal"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                const id = this.id.replace('resetPasswordModal', '');
                const passwordField = document.getElementById('editResetPassword' + id);
                if (passwordField) {
                    document.getElementById('editResetPasswordDisplay' + id).textContent = passwordField.value;
                }
            });
        });
    });
</script>
@endpush
@endsection
@extends('layouts.admin')

@section('title', 'Modifier la transaction')

@section('page-title', 'Modifier la transaction')
@section('page-subtitle', 'Modifier les informations de la transaction')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.dashboard') }}">Finances</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.transactions') }}">Transactions</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.transactions.show', $transaction) }}">Détails</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2 text-warning"></i>
            Modifier la transaction
        </h1>
        <div>
            <a href="{{ route('admin.financial.transactions.show', $transaction) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('admin.financial.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-list me-2"></i> Toutes les transactions
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.financial.transactions.update', $transaction) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Référence (lecture seule) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Référence</label>
                        <input type="text" class="form-control bg-light" value="{{ $transaction->reference }}" readonly disabled>
                        <small class="text-muted">La référence est générée automatiquement</small>
                    </div>

                    <!-- Statut (lecture seule) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Statut</label>
                        <input type="text" class="form-control bg-light" value="{{ ucfirst($transaction->status) }}" readonly disabled>
                        <small class="text-muted">Le statut est modifiable uniquement par approbation/rejet</small>
                    </div>

                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type *</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Sélectionner</option>
                            <option value="income" {{ old('type', $transaction->type) == 'income' ? 'selected' : '' }}>Entrée</option>
                            <option value="expense" {{ old('type', $transaction->type) == 'expense' ? 'selected' : '' }}>Sortie</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Catégorie *</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Sélectionner</option>
                            <optgroup label="Revenus">
                                @foreach($incomeCategories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $transaction->category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Dépenses">
                                @foreach($expenseCategories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $transaction->category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Montant -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Montant *</label>
                        <div class="input-group">
                            <span class="input-group-text">FCFA</span>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                   value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date de la transaction *</label>
                        <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" 
                               value="{{ old('transaction_date', optional($transaction->transaction_date)->format('Y-m-d')) }}" required>
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Membre associé -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Membre associé</label>
                        <select name="member_id" class="form-select @error('member_id') is-invalid @enderror">
                            <option value="">Aucun</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id', $transaction->member_id) == $member->id ? 'selected' : '' }}>
                                    {{ $member->full_name }} ({{ $member->member_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description *</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" required>{{ old('description', $transaction->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reçu actuel -->
                    @if($transaction->receipt_path)
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Reçu actuel</label>
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded">
                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                <div>
                                    <a href="{{ $transaction->receipt_url }}" target="_blank" class="text-primary">
                                        {{ basename($transaction->receipt_path) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Nouveau reçu -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Nouveau reçu <span class="text-muted">(optionnel)</span></label>
                        <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror" 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">
                            @if($transaction->receipt_path)
                                Laissez vide pour conserver le reçu actuel
                            @else
                                Formats acceptés : PDF, JPG, JPEG, PNG (max 2MB)
                            @endif
                        </small>
                        @error('receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Informations système -->
                    <div class="col-md-12">
                        <hr>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="fw-semibold text-muted small">Créé le</label>
                                <p class="mb-0">{{ $transaction->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold text-muted small">Dernière modification</label>
                                <p class="mb-0">{{ $transaction->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold text-muted small">ID</label>
                                <p class="mb-0">#{{ $transaction->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.financial.transactions.show', $transaction) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alertes pour les transactions approuvées -->
    @if($transaction->status === 'approved')
        <div class="alert alert-warning mt-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Transaction approuvée :</strong> Certains champs ne peuvent pas être modifiés. Pour modifier une transaction approuvée, veuillez d'abord la rejeter.
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Validation du formulaire avant soumission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const amount = document.querySelector('input[name="amount"]');
            if (amount && parseFloat(amount.value) <= 0) {
                e.preventDefault();
                alert('Le montant doit être supérieur à 0.');
            }
        });
    });
</script>
@endpush
@extends('layouts.admin')

@section('title', 'Nouvelle transaction')

@section('page-title', 'Nouvelle transaction')
@section('page-subtitle', 'Enregistrer une nouvelle transaction financière')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.dashboard') }}">Finances</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.transactions') }}">Transactions</a></li>
    <li class="breadcrumb-item active">Nouvelle</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-plus-circle me-2 text-primary"></i>
            Nouvelle transaction
        </h1>
        <a href="{{ route('admin.financial.transactions') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
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
            <form action="{{ route('admin.financial.transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Type -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type *</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Sélectionner</option>
                            <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Entrée</option>
                            <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Sortie</option>
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
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Dépenses">
                                @foreach($expenseCategories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                                   value="{{ old('amount') }}" step="0.01" min="0" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date de la transaction *</label>
                        <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" 
                               value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required>
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
                                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
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
                                  rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reçu -->
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Pièce jointe (reçu)</label>
                        <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror" 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Formats acceptés : PDF, JPG, JPEG, PNG (max 2MB)</small>
                        @error('receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.financial.transactions') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
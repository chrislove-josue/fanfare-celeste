@extends('layouts.admin')

@section('title', 'Détails de la transaction')

@section('page-title', 'Détails de la transaction')
@section('page-subtitle', 'Informations complètes de la transaction')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.dashboard') }}">Finances</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.transactions') }}">Transactions</a></li>
    <li class="breadcrumb-item active">Détails</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-receipt me-2 text-primary"></i>
            {{ $transaction->reference }}
        </h1>
        <div>
            <a href="{{ route('admin.financial.transactions') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            @if($transaction->status !== 'approved')
                <a href="{{ route('admin.financial.transactions.edit', $transaction) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i> Modifier
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Informations de la transaction
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Référence</label>
                            <p class="mb-0">{{ $transaction->reference }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Statut</label>
                            <p class="mb-0">
                                @if($transaction->status === 'approved')
                                    <span class="badge bg-success">Approuvé</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Type</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                    {{ $transaction->type === 'income' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Catégorie</label>
                            <p class="mb-0">{{ $transaction->category }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Montant</label>
                            <p class="mb-0 fw-bold fs-5">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Date</label>
                            <p class="mb-0">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold text-muted">Description</label>
                            <p class="mb-0">{{ $transaction->description }}</p>
                        </div>
                        @if($transaction->member)
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted">Membre associé</label>
                            <p class="mb-0">
                                <a href="{{ route('admin.members.show', $transaction->member) }}">
                                    {{ $transaction->member->full_name }}
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($transaction->rejection_reason)
                        <div class="col-md-12">
                            <label class="fw-semibold text-muted">Motif du rejet</label>
                            <p class="mb-0 text-danger">{{ $transaction->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @if($transaction->receipt_path)
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                        Reçu
                    </h5>
                </div>
                <div class="card-body text-center">
                    <a href="{{ $transaction->receipt_url }}" target="_blank" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i> Voir le reçu
                    </a>
                </div>
            </div>
            @endif

            @if($transaction->status === 'pending')
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gavel me-2 text-warning"></i>
                        Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.financial.transactions.approve', $transaction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i> Approuver
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i> Rejeter
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2 text-info"></i>
                        Informations système
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="fw-semibold text-muted small">Créé le</label>
                        <p class="mb-0">{{ $transaction->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-2">
                        <label class="fw-semibold text-muted small">Modifié le</label>
                        <p class="mb-0">{{ $transaction->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @if($transaction->approved_at)
                    <div>
                        <label class="fw-semibold text-muted small">Approuvé le</label>
                        <p class="mb-0">{{ $transaction->approved_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rejeter -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.financial.transactions.reject', $transaction) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle me-2 text-danger"></i>
                        Rejeter la transaction
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif du rejet *</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i> Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
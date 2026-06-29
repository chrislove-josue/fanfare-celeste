@extends('layouts.admin')

@section('title', 'Transactions financières')

@section('page-title', 'Transactions')
@section('page-subtitle', 'Gestion des transactions financières')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.dashboard') }}">Finances</a></li>
    <li class="breadcrumb-item active">Transactions</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-receipt me-2 text-primary"></i>
            Transactions
        </h1>
        <a href="{{ route('admin.financial.transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nouvelle transaction
        </a>
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

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.financial.transactions') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Recherche</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Réf, description...">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Entrée</option>
                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Sortie</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Période</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Début">
                        <span class="input-group-text">à</span>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="Fin">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.financial.transactions') }}" class="btn btn-secondary" title="Réinitialiser">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle p-2 text-white">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total entrées</div>
                        <div class="h5 mb-0">{{ number_format($totalIncome ?? 0, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-danger rounded-circle p-2 text-white">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total sorties</div>
                        <div class="h5 mb-0">{{ number_format($totalExpense ?? 0, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2 text-white">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Solde</div>
                        <div class="h5 mb-0">{{ number_format($balance ?? 0, 0, ',', ' ') }} FCFA</div>
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
                        <div class="h5 mb-0">{{ $transactions->where('status', 'pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des transactions -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Type</th>
                            <th>Catégorie</th>
                            <th>Montant</th>
                            <th>Membre</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $transaction->reference }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                    {{ $transaction->type === 'income' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td>{{ $transaction->category }}</td>
                            <td class="fw-bold">
                                {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td>
                                @if($transaction->member)
                                    <a href="{{ route('admin.members.show', $transaction->member) }}" class="text-decoration-none">
                                        {{ $transaction->member->full_name }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->status === 'approved')
                                    <span class="badge bg-success">Approuvé</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </td>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Bouton Voir -->
                                    <a href="{{ route('admin.financial.transactions.show', $transaction) }}" 
                                       class="btn btn-info" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Bouton Modifier (uniquement si non approuvé) -->
                                    @if($transaction->status !== 'approved')
                                        <a href="{{ route('admin.financial.transactions.edit', $transaction) }}" 
                                           class="btn btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    
                                    <!-- Bouton Approuver (si en attente) -->
                                    @if($transaction->status === 'pending')
                                        <form action="{{ route('admin.financial.transactions.approve', $transaction) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Approuver" onclick="return confirm('Approuver cette transaction ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- Bouton Rejeter (si en attente) -->
                                    @if($transaction->status === 'pending')
                                        <button type="button" class="btn btn-danger" title="Rejeter" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $transaction->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    <!-- Bouton Reçu (si existe) -->
                                    @if($transaction->receipt_path)
                                        <a href="{{ $transaction->receipt_url }}" target="_blank" class="btn btn-secondary" title="Voir le reçu">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                </div>

                                <!-- Modal Rejeter -->
                                <div class="modal fade" id="rejectModal{{ $transaction->id }}" tabindex="-1">
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
                                                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Expliquez pourquoi cette transaction est rejetée..." required></textarea>
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-receipt fa-3x d-block mb-3 text-muted"></i>
                                <h5 class="text-muted">Aucune transaction trouvée</h5>
                                <p class="text-muted small">Commencez par créer une nouvelle transaction.</p>
                                <a href="{{ route('admin.financial.transactions.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i> Nouvelle transaction
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small">
                    Affichage de {{ $transactions->firstItem() ?? 0 }} à {{ $transactions->lastItem() ?? 0 }} sur {{ $transactions->total() }} transactions
                </span>
            </div>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush
@endsection
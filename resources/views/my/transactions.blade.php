@extends('layouts.admin')

@section('title', 'Mes transactions')

@section('page-title', 'Mes transactions')
@section('page-subtitle', 'Historique de vos transactions')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('my.profile') }}">Mon profil</a></li>
    <li class="breadcrumb-item active">Transactions</li>
@endsection

@section('content')
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-success rounded-circle p-2 text-white">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total entrées</div>
                    <div class="h5 mb-0 text-success">{{ number_format($totalIncome ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-danger rounded-circle p-2 text-white">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total sorties</div>
                    <div class="h5 mb-0 text-danger">{{ number_format($totalExpenses ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
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
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>
            Historique des transactions
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $transaction->description ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ $transaction->type === 'income' ? 'Entrée' : 'Sortie' }}
                            </span>
                        </td>
                        <td class="fw-semibold text-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}
                            {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td>
                            @if($transaction->status === 'approved')
                                <span class="badge bg-success">Approuvé</span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @else
                                <span class="badge bg-secondary">{{ $transaction->status ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-receipt fa-2x d-block mb-2"></i>
                            Aucune transaction enregistrée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="p-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
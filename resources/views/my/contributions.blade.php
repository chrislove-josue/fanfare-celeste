@extends('layouts.admin')

@section('title', 'Mes cotisations')

@section('page-title', 'Mes cotisations')
@section('page-subtitle', 'Historique de vos contributions')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('my.profile') }}">Mon profil</a></li>
    <li class="breadcrumb-item active">Cotisations</li>
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
    <!-- Statistiques -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-primary rounded-circle p-2 text-white">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total cotisé</div>
                    <div class="h5 mb-0">{{ number_format($totalContributions ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-success rounded-circle p-2 text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Ce mois-ci</div>
                    <div class="h5 mb-0">{{ number_format($monthlyContributions ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-info rounded-circle p-2 text-white">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total opérations</div>
                    <div class="h5 mb-0">{{ $contributions->total() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>
            Historique des cotisations
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Référence</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contributions as $contribution)
                    <tr>
                        <td>{{ $contribution->payment_date?->format('d/m/Y') ?? '-' }}</td>
                        <td class="fw-semibold">{{ number_format($contribution->amount, 0, ',', ' ') }} FCFA</td>
                        <td>{{ ucfirst($contribution->type ?? 'Cotisation') }}</td>
                        <td>
                            @if($contribution->status === 'paid')
                                <span class="badge bg-success">Payé</span>
                            @elseif($contribution->status === 'pending')
                                <span class="badge bg-warning">En attente</span>
                            @else
                                <span class="badge bg-secondary">{{ $contribution->status ?? '-' }}</span>
                            @endif
                        </td>
                        <td>{{ $contribution->reference ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-coins fa-2x d-block mb-2"></i>
                            Aucune cotisation enregistrée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contributions->hasPages())
            <div class="p-3">
                {{ $contributions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
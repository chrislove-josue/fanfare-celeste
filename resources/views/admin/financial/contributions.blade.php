@extends('layouts.admin')

@section('title', 'Gestion des cotisations')

@section('page-title', 'Cotisations')
@section('page-subtitle', 'Gestion des cotisations des membres')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.financial.dashboard') }}">Finances</a></li>
    <li class="breadcrumb-item active">Cotisations</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-hand-holding-heart me-2 text-primary"></i>
            Cotisations
        </h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordContributionModal">
            <i class="fas fa-plus me-2"></i> Enregistrer une cotisation
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Cotisations mensuelles</div>
                    <div class="h5 mb-0">{{ number_format($totalMonthlyContributions ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Cotisations spéciales</div>
                    <div class="h5 mb-0">{{ number_format($totalSpecialContributions ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Dons</div>
                    <div class="h5 mb-0">{{ number_format($totalDonations ?? 0, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Membres sans cotisation</div>
                    <div class="h5 mb-0">{{ $unpaidMembers->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des cotisations -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Reçu N°</th>
                            <th>Membre</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Mois</th>
                            <th>Année</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contributions as $contribution)
                        <tr>
                            <td>{{ $contribution->receipt_number }}</td>
                            <td>{{ $contribution->member->full_name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($contribution->type) }}</td>
                            <td class="fw-bold">{{ number_format($contribution->amount, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $contribution->month ? str_pad($contribution->month, 2, '0', STR_PAD_LEFT) : '-' }}</td>
                            <td>{{ $contribution->year ?? '-' }}</td>
                            <td>{{ $contribution->payment_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-success">Payé</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-hand-holding-heart fa-2x d-block mb-2"></i>
                                Aucune cotisation enregistrée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $contributions->links() }}
        </div>
    </div>
</div>

<!-- Modal Enregistrer une cotisation -->
<div class="modal fade" id="recordContributionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.financial.contributions.record') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-hand-holding-heart me-2 text-primary"></i>
                        Enregistrer une cotisation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Membre *</label>
                        <select name="member_id" class="form-select" required>
                            <option value="">Sélectionner</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->member_number }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="monthly">Mensuelle</option>
                            <option value="special">Spéciale</option>
                            <option value="donation">Don</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Montant *</label>
                        <input type="number" name="amount" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mois</label>
                            <select name="month" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == $currentMonth ? 'selected' : '' }}>
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Année</label>
                            <select name="year" class="form-select">
                                @for($i = $currentYear - 2; $i <= $currentYear + 1; $i++)
                                    <option value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date de paiement *</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Méthode de paiement *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Espèces</option>
                            <option value="bank_transfer">Virement bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="check">Chèque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
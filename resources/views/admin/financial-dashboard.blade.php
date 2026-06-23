@extends('layouts.admin')

@section('title', 'Gestion financière - Dashboard')

@section('page-title', 'Gestion financière')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard Financier</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-xl-4 col-md-6">
        <div class="stat-card" style="border-left-color: #3946B2;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($balance, 0, ',', ' ') }}</div>
                    <div class="stat-label">Solde Total</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #3946B2, #6366f1);">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card" style="border-left-color: #40A81B;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($monthlyBalance, 0, ',', ' ') }}</div>
                    <div class="stat-label">Solde du mois</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #40A81B, #65c53c);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card" style="border-left-color: #8B5CF6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($recentTransactions->count()) }}</div>
                    <div class="stat-label">Transactions récentes</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #a78bfa);">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Évolution Mensuelle des Finances</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Dernières Transactions</h5>
                <a href="{{ route('admin.financial.transactions') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentTransactions as $transaction)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $transaction->description }}</div>
                                <div class="text-muted small">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                            </div>
                            <div class="text-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted">Aucune transaction</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1">Actions rapides</h5>
                        <p class="text-muted small mb-0">Gérez les transactions financières</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.financial.transactions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle transaction
                        </a>
                        <a href="{{ route('admin.financial.contributions') }}" class="btn btn-success">
                            <i class="fas fa-hand-holding-heart"></i> Cotisations
                        </a>
                        <a href="{{ route('admin.financial.report') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Rapport
                        </a>
                        <a href="{{ route('admin.financial.export.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyEvolution);
    
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [
                {
                    label: 'Revenus',
                    data: monthlyData.map(d => d.income),
                    backgroundColor: 'rgba(57, 70, 178, 0.7)',
                    borderColor: '#3946B2',
                    borderWidth: 2
                },
                {
                    label: 'Dépenses',
                    data: monthlyData.map(d => d.expense),
                    backgroundColor: 'rgba(255, 107, 53, 0.7)',
                    borderColor: '#FF6B35',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FCFA';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
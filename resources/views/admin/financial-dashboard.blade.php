@extends('layouts.admin')

@section('title', 'Gestion financière - Dashboard')

@section('page-title', 'Gestion financière')
@section('page-subtitle', 'Suivez et gérez les flux financiers de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Finances</li>
@endsection

@section('content')
<style>
    /* ============================================
       STYLES PRO POUR LA GESTION FINANCIÈRE
    ============================================ */
    
    /* === STATS CARDS === */
    .stat-card-pro {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .stat-card-pro::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--stat-color, #3946B2);
    }
    
    .stat-card-pro:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card-pro .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #fff;
        flex-shrink: 0;
        background: var(--stat-color, #3946B2);
        box-shadow: 0 4px 12px rgba(var(--stat-color-rgb, 57, 70, 178), 0.3);
    }
    
    .stat-card-pro .stat-number {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.2;
    }
    
    .stat-card-pro .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .stat-card-pro .stat-trend {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .stat-card-pro .stat-trend.up {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .stat-card-pro .stat-trend.down {
        background: #fee2e2;
        color: #dc2626;
    }
    
    /* === CARTE GRAPHIQUE === */
    .chart-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .chart-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    .chart-card .card-header-custom {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .chart-card .card-header-custom h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .chart-card .card-header-custom h5 i {
        color: #3946B2;
    }
    
    .chart-card .card-body-custom {
        padding: 1.5rem;
    }
    
    /* === LISTE DES TRANSACTIONS === */
    .transaction-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .transaction-list::-webkit-scrollbar {
        width: 4px;
    }
    
    .transaction-list::-webkit-scrollbar-track {
        background: #f1f3f5;
        border-radius: 10px;
    }
    
    .transaction-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f3f5;
        transition: background 0.2s ease;
        cursor: default;
    }
    
    .transaction-item:hover {
        background: #f8fafc;
    }
    
    .transaction-item:last-child {
        border-bottom: none;
    }
    
    .transaction-item .transaction-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex: 1;
        min-width: 0;
    }
    
    .transaction-item .transaction-info .title {
        font-weight: 500;
        color: #1a1a2e;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .transaction-item .transaction-info .meta {
        font-size: 0.7rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .transaction-item .transaction-info .meta .category {
        background: #eef2ff;
        color: #3946B2;
        padding: 1px 8px;
        border-radius: 10px;
        font-weight: 500;
    }
    
    .transaction-item .transaction-amount {
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
        margin-left: 1rem;
    }
    
    .transaction-item .transaction-amount.income {
        color: #16a34a;
    }
    
    .transaction-item .transaction-amount.expense {
        color: #dc2626;
    }
    
    .transaction-item .transaction-badge {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-right: 0.5rem;
    }
    
    .transaction-item .transaction-badge.income {
        background: #22c55e;
    }
    
    .transaction-item .transaction-badge.expense {
        background: #ef4444;
    }
    
    /* === ACTIONS RAPIDES === */
    .quick-actions-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .quick-actions-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    .quick-actions-card .quick-title h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .quick-actions-card .quick-title p {
        margin: 0;
        color: #6b7280;
        font-size: 0.8rem;
    }
    
    .quick-actions-card .quick-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .quick-actions-card .quick-buttons .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1.2rem;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .quick-actions-card .quick-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .stat-card-pro .stat-number {
            font-size: 1.3rem;
        }
        .stat-card-pro .stat-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .quick-actions-card {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .quick-actions-card .quick-buttons {
            justify-content: center;
        }
        .transaction-item {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .transaction-item .transaction-amount {
            margin-left: 0;
            width: 100%;
            text-align: right;
        }
    }
</style>

<!-- ========================================== -->
<!-- STATISTIQUES PRINCIPALES                    -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #3946B2; --stat-color-rgb: 57, 70, 178;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($balance, 0, ',', ' ') }}</div>
                    <div class="stat-label">Solde Total</div>
                    <div class="mt-1">
                        <span class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +8% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($monthlyBalance, 0, ',', ' ') }}</div>
                    <div class="stat-label">Solde du mois</div>
                    <div class="mt-1">
                        <span class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +12% vs mois dernier
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #8b5cf6; --stat-color-rgb: 139, 92, 246;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($recentTransactions->count()) }}</div>
                    <div class="stat-label">Transactions récentes</div>
                    <div class="mt-1">
                        <span class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +5% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- GRAPHIQUE + TRANSACTIONS                    -->
<!-- ========================================== -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="chart-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-chart-line"></i>
                    Évolution Mensuelle des Finances
                </h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; color: #3946B2;"></i>
                        Revenus
                    </span>
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; color: #FF6B35;"></i>
                        Dépenses
                    </span>
                </div>
            </div>
            <div class="card-body-custom">
                <canvas id="monthlyChart" height="280"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="chart-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-list-ul"></i>
                    Dernières Transactions
                </h5>
                <a href="{{ route('admin.financial.transactions') }}" class="btn btn-sm btn-primary">
                    Voir tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body-custom p-0">
                <div class="transaction-list">
                    @forelse($recentTransactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="d-flex align-items-center">
                                <span class="transaction-badge {{ $transaction->type === 'income' ? 'income' : 'expense' }}"></span>
                                <span class="title">{{ $transaction->description }}</span>
                            </div>
                            <div class="meta">
                                <span>
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $transaction->transaction_date->format('d/m/Y') }}
                                </span>
                                @if($transaction->category)
                                    <span class="category">{{ $transaction->category }}</span>
                                @endif
                                @if($transaction->member)
                                    <span>
                                        <i class="fas fa-user me-1"></i>
                                        {{ $transaction->member->name ?? $transaction->member->first_name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="transaction-amount {{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }} 
                            {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Aucune transaction récente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- STATISTIQUES COMPLÉMENTAIRES               -->
<!-- ========================================== -->
<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper" style="background: #dcfce7; color: #22c55e; box-shadow: none;">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalIncome ?? 0, 0, ',', ' ') }}</div>
                    <div class="stat-label">Total Revenus</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card-pro" style="--stat-color: #ef4444; --stat-color-rgb: 239, 68, 68;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper" style="background: #fee2e2; color: #ef4444; box-shadow: none;">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalExpense ?? 0, 0, ',', ' ') }}</div>
                    <div class="stat-label">Total Dépenses</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card-pro" style="--stat-color: #f59e0b; --stat-color-rgb: 245, 158, 11;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper" style="background: #fef3c7; color: #f59e0b; box-shadow: none;">
                    <i class="fas fa-percent"></i>
                </div>
                <div>
                    <div class="stat-number">{{ isset($totalIncome) && $totalIncome > 0 ? round(($totalExpense ?? 0) / $totalIncome * 100) : 0 }}%</div>
                    <div class="stat-label">Taux de dépenses</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ACTIONS RAPIDES                            -->
<!-- ========================================== -->
<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="quick-actions-card">
            <div class="quick-title">
                <h5>
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Actions rapides
                </h5>
                <p>Gérez les transactions financières en un clic</p>
            </div>
            <div class="quick-buttons">
                <a href="{{ route('admin.financial.transactions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nouvelle transaction
                </a>
                <a href="{{ route('admin.financial.contributions') }}" class="btn btn-success">
                    <i class="fas fa-hand-holding-heart me-1"></i> Cotisations
                </a>
                <a href="{{ route('admin.financial.report') }}" class="btn btn-info text-white">
                    <i class="fas fa-chart-bar me-1"></i> Rapport
                </a>
                <a href="{{ route('admin.financial.export.pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('admin.financial.export.excel') }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // GRAPHIQUE D'ÉVOLUTION MENSUELLE
    // ==========================================
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
                    backgroundColor: 'rgba(57, 70, 178, 0.75)',
                    borderColor: '#3946B2',
                    borderWidth: 2,
                    borderRadius: 6,
                    barPercentage: 0.6,
                },
                {
                    label: 'Dépenses',
                    data: monthlyData.map(d => d.expense),
                    backgroundColor: 'rgba(255, 107, 53, 0.75)',
                    borderColor: '#FF6B35',
                    borderWidth: 2,
                    borderRadius: 6,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + 
                                   context.parsed.y.toLocaleString() + ' FCFA';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false,
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M FCFA';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'k FCFA';
                            }
                            return value + ' FCFA';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
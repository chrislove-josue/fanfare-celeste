@extends('layouts.admin')

@section('title', 'Rapport financier')
@section('page-title', 'Rapport financier')
@section('page-subtitle', 'Période du ' . $startDate . ' au ' . $endDate)

@section('styles')
<style>
    .summary-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .summary-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .summary-box .label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .summary-box .value {
        font-size: 24px;
        font-weight: 700;
        margin-top: 8px;
    }
    .text-success { color: #28a745; }
    .text-danger { color: #dc3545; }
    .text-primary { color: #3946B2; }
    
    .badge-income {
        background: #d4edda;
        color: #155724;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-expense {
        background: #f8d7da;
        color: #721c24;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .table thead th {
        background: #3946B2;
        color: #fff;
        border: none;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    .table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .print-btn {
        background: #3946B2;
        color: #fff;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    .print-btn:hover {
        background: #283593;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(57, 70, 178, 0.3);
    }
    .print-btn i {
        margin-right: 8px;
    }
    
    .category-section {
        margin-top: 30px;
    }
    .category-section h4 {
        color: #3946B2;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .report-meta {
        color: #6c757d;
        font-size: 13px;
    }
    .report-meta i {
        margin-right: 5px;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        .summary-box {
            border: 1px solid #ddd !important;
        }
        .table thead th {
            background: #3946B2 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .badge-income, .badge-expense {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.financial.dashboard') }}" class="text-decoration-none">
        <i class="fas fa-coins"></i> Finances
    </a>
</li>
<li class="breadcrumb-item active" aria-current="page">Rapport financier</li>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- En-tête du rapport -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-custom rounded-circle p-3 text-white">
                    <i class="fas fa-chart-pie fa-2x"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Rapport financier</h4>
                    <p class="report-meta mb-0">
                        <i class="fas fa-calendar-alt"></i> 
                        Période du <strong>{{ $startDate }}</strong> au <strong>{{ $endDate }}</strong>
                    </p>
                    <p class="report-meta mb-0">
                        <i class="fas fa-clock"></i> 
                        Généré le {{ date('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Imprimer
            </button>
            <a href="{{ route('admin.financial.export.pdf', ['start' => $startDate, 'end' => $endDate]) }}" 
               class="btn btn-outline-primary ms-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.financial.export.excel', ['start' => $startDate, 'end' => $endDate]) }}" 
               class="btn btn-outline-success ms-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- Résumé -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="summary-box">
                <div class="label">
                    <i class="fas fa-arrow-up text-success me-1"></i> Total Revenus
                </div>
                <div class="value text-success">
                    {{ number_format($totalIncome, 0, ',', ' ') }} FCFA
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <div class="label">
                    <i class="fas fa-arrow-down text-danger me-1"></i> Total Dépenses
                </div>
                <div class="value text-danger">
                    {{ number_format($totalExpenses, 0, ',', ' ') }} FCFA
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <div class="label">
                    <i class="fas fa-wallet text-primary me-1"></i> Solde
                </div>
                <div class="value text-primary">
                    {{ number_format($balance, 0, ',', ' ') }} FCFA
                </div>
            </div>
        </div>
    </div>

    <!-- Détail des transactions -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list-ul text-primary me-2"></i>
                    Détail des transactions
                </h5>
                <span class="badge bg-secondary">
                    {{ $transactions->count() }} transaction(s)
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Description</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th class="text-end">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <span class="fw-medium">
                                    {{ $transaction->transaction_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded" style="font-size: 12px;">
                                    {{ $transaction->reference }}
                                </code>
                            </td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $transaction->category }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $transaction->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                    <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                                    {{ $transaction->type === 'income' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">
                                <span class="{{ $transaction->type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}
                                    {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x d-block mb-3 opacity-50"></i>
                                    <p class="mb-0">Aucune transaction dans cette période</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold" style="background: #f8f9fa;">
                            <td colspan="5" class="text-end">TOTAL :</td>
                            <td class="text-end">
                                <span class="text-success">{{ number_format($totalIncome, 0, ',', ' ') }} FCFA</span>
                                /
                                <span class="text-danger">{{ number_format($totalExpenses, 0, ',', ' ') }} FCFA</span>
                                <br>
                                <small class="text-primary">Solde : {{ number_format($balance, 0, ',', ' ') }} FCFA</small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="text-center mt-4 no-print">
        <p class="text-muted" style="font-size: 12px;">
            <i class="fas fa-lock me-1"></i> 
            Ce document est confidentiel et destiné à un usage interne.
            <br>
            Fanfare Céleste - La Trompette de Dieu de l'Atlantique
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Pour le bouton d'impression
    document.querySelector('.print-btn')?.addEventListener('click', function() {
        window.print();
    });
</script>
@endsection
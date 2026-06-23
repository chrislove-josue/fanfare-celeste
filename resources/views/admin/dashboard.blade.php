@extends('layouts.admin')

@section('title', 'Tableau de bord - Admin')

@section('page-title', 'Tableau de bord')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($totalMembers) }}</div>
                    <div class="stat-label">Total Membres</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #3946B2, #6366f1);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color: #40A81B;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($activeMembers) }}</div>
                    <div class="stat-label">Membres Actifs</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #40A81B, #65c53c);">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color: #FF6B35;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($pendingMembers) }}</div>
                    <div class="stat-label">En attente</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF6B35, #ff8f5e);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="border-left-color: #8B5CF6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ number_format($balance, 0, ',', ' ') }}</div>
                    <div class="stat-label">Solde Total</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #a78bfa);">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mt-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Évolution Mensuelle</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Membres par Section</h5>
            </div>
            <div class="card-body">
                <canvas id="sectionChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row g-4 mt-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Derniers Membres</h5>
                <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Section</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers as $member)
                            <tr>
                                <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                                <td>{{ $member->section ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $member->status === 'active' ? 'success' : ($member->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($member->status ?? 'inactif') }}
                                    </span>
                                </td>
                                <td>{{ $member->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucun membre récent</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Dernières Transactions</h5>
                <a href="{{ route('admin.financial.transactions') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                        {{ $transaction->type === 'income' ? 'Entrée' : 'Sortie' }}
                                    </span>
                                </td>
                                <td class="text-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }} {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune transaction récente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="display-6 text-primary">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Événements à venir</div>
                    <div class="h5 mb-0">{{ number_format($upcomingEvents) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="display-6 text-success">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Événements ce mois</div>
                    <div class="h5 mb-0">{{ number_format($eventsThisMonth) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="display-6 text-info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Nouveaux membres ce mois</div>
                    <div class="h5 mb-0">{{ number_format($newMembersThisMonth) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Monthly Evolution Chart
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
    
    // Section Chart
    const sectionCtx = document.getElementById('sectionChart').getContext('2d');
    const sectionData = @json($membersBySection);
    
    const colors = ['#3946B2', '#40A81B', '#FF6B35', '#8B5CF6', '#F59E0B', '#EC4899', '#06B6D4'];
    
    new Chart(sectionCtx, {
        type: 'doughnut',
        data: {
            labels: sectionData.map(d => d.section || 'Non défini'),
            datasets: [{
                data: sectionData.map(d => d.total),
                backgroundColor: colors.slice(0, sectionData.length),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection
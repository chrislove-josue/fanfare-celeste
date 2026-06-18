@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Aside (Dashboard) -->
        <aside class="col-lg-3">
            <div class="card shadow-sm position-sticky" style="top: 90px;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="fw-bold">Gestion</div>
                            <div class="text-muted small">Rôles & Permissions</div>
                        </div>
                        <i class="fas fa-shield-alt text-primary" style="font-size: 1.2rem;"></i>
                    </div>

                    <div class="mb-3">
                        <a class="d-block text-decoration-none mb-2 px-3 py-2 rounded"
                           href="{{ route('admin.dashboard') }}"
                           style="background: #f4f6ff; color:#1a237e; font-weight:600;">
                            <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                        </a>
                    </div>

                    <div class="mb-3">
                        <div class="text-uppercase small text-muted mb-2">Membres</div>
                        @can('view members')
                            <a class="d-block text-decoration-none px-3 py-2 rounded mb-2" href="{{ route('admin.members.index') }}">
                                <i class="fas fa-users me-2"></i> Liste des membres
                            </a>
                        @endcan

                        @can('approve members')
                            <a class="d-block text-decoration-none px-3 py-2 rounded mb-2" href="{{ route('admin.members.index') }}">
                                <i class="fas fa-user-check me-2"></i> Approbations
                            </a>
                        @endcan

                    </div>

                    <div class="mb-3">
                        <div class="text-uppercase small text-muted mb-2">Finances</div>
                        @can('view finances')
                            <a class="d-block text-decoration-none px-3 py-2 rounded mb-2" href="{{ route('admin.financial.dashboard') }}">
                                <i class="fas fa-chart-line me-2"></i> Aperçu finances
                            </a>
                        @endcan

                        @can('approve transactions')
                            <a class="d-block text-decoration-none px-3 py-2 rounded mb-2" href="{{ route('admin.financial.transactions') }}">
                                <i class="fas fa-check-circle me-2"></i> Approbation transactions
                            </a>
                        @endcan
                    </div>

                    <div class="mb-3">
                        <div class="text-uppercase small text-muted mb-2">Événements</div>
                        @can('view events')
                            <a class="d-block text-decoration-none px-3 py-2 rounded mb-2" href="{{ route('admin.events.index') }}">
                                <i class="fas fa-calendar me-2"></i> Liste des événements
                            </a>
                        @endcan

                        @can('manage participations')
                            <a class="d-block text-decoration-none px-3 py-2 rounded" href="{{ route('admin.events.index') }}">
                                <i class="fas fa-users me-2"></i> Participations
                            </a>
                        @endcan


                    </div>

                    <div class="mb-0">
                        <div class="text-uppercase small text-muted mb-2">Bureau</div>
                        @can('manage committee')
                            <a class="d-block text-decoration-none px-3 py-2 rounded" href="{{ route('admin.committee.index') }}">
                                <i class="fas fa-user-tie me-2"></i> Bureau exécutif
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <main class="col-lg-9">
            <h1 class="mb-4">Tableau de bord</h1>

            <!-- Statistiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Membres totaux</h6>
                                    <h2 class="mb-0">{{ $totalMembers }}</h2>
                                </div>
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                            <div class="mt-3">
                                <small>Actifs: {{ $activeMembers }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Solde disponible</h6>
                                    <h2 class="mb-0">{{ number_format($balance, 0, ',', ' ') }} FCFA</h2>
                                </div>
                                <i class="fas fa-chart-line fa-3x opacity-50"></i>
                            </div>
                            <div class="mt-3">
                                <small>Ce mois: {{ number_format($monthlyBalance, 0, ',', ' ') }} FCFA</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Événements à venir</h6>
                                    <h2 class="mb-0">{{ $upcomingEvents }}</h2>
                                </div>
                                <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                            </div>
                            <div class="mt-3">
                                <small>Ce mois: {{ $eventsThisMonth }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Nouvelles adhésions</h6>
                                    <h2 class="mb-0">{{ $newMembersThisMonth }}</h2>
                                </div>
                                <i class="fas fa-user-plus fa-3x opacity-50"></i>
                            </div>
                            <div class="mt-3">
                                <small>En attente: {{ $pendingMembers }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Évolution des finances</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="financialChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Membres par section</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="sectionsChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières activités -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Derniers membres inscrits</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Instrument</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentMembers as $member)
                                        <tr>
                                            <td>{{ $member->full_name }}</td>
                                            <td>{{ $member->instrument ?? '-' }}</td>
                                            <td>{{ $member->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Dernières transactions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Montant</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ Str::limit($transaction->description, 30) }}</td>
                                            <td class="{{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->type == 'income' ? '+' : '-' }}
                                                {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const financialCtx = document.getElementById('financialChart').getContext('2d');
    new Chart(financialCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Revenus',
                data: @json(array_column($monthlyEvolution, 'income')),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40,167,69,0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Dépenses',
                data: @json(array_column($monthlyEvolution, 'expense')),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220,53,69,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    const sectionsCtx = document.getElementById('sectionsChart').getContext('2d');
    new Chart(sectionsCtx, {
        type: 'doughnut',
        data: {
            labels: @json($membersBySection->pluck('section')),
            datasets: [{
                data: @json($membersBySection->pluck('total')),
                backgroundColor: ['#FFD700', '#1a237e', '#d32f2f', '#28a745', '#ffc107', '#17a2b8']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection

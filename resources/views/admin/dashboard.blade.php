@extends('layouts.admin')

@section('title', 'Tableau de bord - Admin')

@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de votre activité')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<style>
    /* ============================================
       STYLES PRO POUR LE DASHBOARD
    ============================================ */
    
    /* === CARTES DE STATISTIQUES === */
    .stat-card-pro {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0, 0, 0, 0.04);
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
        height: 4px;
        background: var(--stat-color, #3946B2);
        border-radius: 16px 16px 0 0;
    }
    
    .stat-card-pro:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card-pro .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        flex-shrink: 0;
        background: var(--stat-color, #3946B2);
        box-shadow: 0 4px 12px rgba(var(--stat-color-rgb, 57, 70, 178), 0.35);
        transition: transform 0.3s ease;
    }
    
    .stat-card-pro:hover .stat-icon-wrapper {
        transform: scale(1.08) rotate(-3deg);
    }
    
    .stat-card-pro .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }
    
    .stat-card-pro .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
        margin-top: 4px;
    }
    
    .stat-card-pro .stat-trend {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 10px;
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
    
    /* === CARTES D'ACTIVITÉ === */
    .activity-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .activity-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }
    
    .activity-card .card-header-custom {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
    }
    
    .activity-card .card-header-custom h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .activity-card .card-header-custom h5 i {
        color: #3946B2;
        font-size: 1.1rem;
    }
    
    .activity-card .card-body-custom {
        padding: 0;
    }
    
    /* === TABLEAUX === */
    .table-dashboard {
        margin: 0;
    }
    
    .table-dashboard thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 12px 16px;
        border-bottom: 2px solid #eef2f6;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    
    .table-dashboard tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f3f5;
        color: #1a1a2e;
    }
    
    .table-dashboard tbody tr {
        transition: background 0.2s ease;
        cursor: default;
    }
    
    .table-dashboard tbody tr:hover {
        background: #f8fafc;
    }
    
    .table-dashboard tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* === BADGES === */
    .badge-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    
    .badge-status.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .badge-status.pending {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-status.inactive {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .badge-status.completed {
        background: #dbeafe;
        color: #2563eb;
    }
    
    /* === CARTES RAPIDES === */
    .quick-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .quick-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .quick-card .quick-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    
    .quick-card .quick-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.2;
    }
    
    .quick-card .quick-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    /* === MISE EN PAGE === */
    .dashboard-header {
        margin-bottom: 2rem;
    }
    
    .dashboard-header h4 {
        font-weight: 700;
        color: #1a1a2e;
    }
    
    .dashboard-header p {
        color: #6b7280;
        margin: 0;
    }
    
    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .stat-card-pro .stat-number {
            font-size: 1.5rem;
        }
        .stat-card-pro .stat-icon-wrapper {
            width: 42px;
            height: 42px;
            font-size: 1rem;
        }
        .quick-card .quick-number {
            font-size: 1.2rem;
        }
        .quick-card .quick-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .activity-card .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<!-- ========================================== -->
<!-- EN-TÊTE DU DASHBOARD                      -->
<!-- ========================================== -->
<div class="dashboard-header d-flex flex-wrap align-items-center justify-content-between">
    <div>
        <h4 class="mb-1">
            <i class="fas fa-chart-pie me-2 text-primary"></i>
            Vue d'ensemble
        </h4>
        <p class="text-muted">
            <i class="far fa-calendar-alt me-1"></i>
            {{ now()->format('l d F Y') }} — 
            <span class="fw-medium">Bienvenue dans votre espace d'administration</span>
        </p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="{{ route('admin.members.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus me-1"></i> Nouveau membre
        </a>
        <a href="{{ route('admin.events.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-calendar-plus me-1"></i> Nouvel événement
        </a>
    </div>
</div>

<!-- ========================================== -->
<!-- STATISTIQUES PRINCIPALES                    -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <!-- Total Membres -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #3946B2; --stat-color-rgb: 57, 70, 178;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalMembers) }}</div>
                    <div class="stat-label">Membres totaux</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Membres Actifs -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($activeMembers) }}</div>
                    <div class="stat-label">Membres actifs</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- En Attente -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #f59e0b; --stat-color-rgb: 245, 158, 11;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($pendingMembers) }}</div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Événements -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #8b5cf6; --stat-color-rgb: 139, 92, 246;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalEvents) }}</div>
                    <div class="stat-label">Événements totaux</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- GRAPHIQUES                                 -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="activity-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-pie-chart"></i>
                    Répartition par section
                </h5>
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                    {{ $totalMembers }} membres
                </span>
            </div>
            <div class="p-4">
                <canvas id="sectionChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="activity-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-chart-bar"></i>
                    Évolution des inscriptions
                </h5>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    +{{ $newMembersThisMonth }} ce mois
                </span>
            </div>
            <div class="p-4">
                <canvas id="membersChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- ACTIVITÉS RÉCENTES                         -->
<!-- ========================================== -->
<div class="row g-4">
    <!-- Derniers Membres -->
    <div class="col-xl-6">
        <div class="activity-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-user-plus"></i>
                    Derniers membres inscrits
                </h5>
                <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-primary">
                    Voir tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table table-dashboard mb-0">
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
                                <td class="fw-medium">
                                    <span class="d-flex align-items-center gap-2">
                                        @if($member->photo_url)
                                            <img src="{{ $member->photo_url }}" class="rounded-circle" width="28" height="28" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.7rem; color: #6b7280;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        {{ $member->first_name }} {{ $member->last_name }}
                                    </span>
                                </td>
                                <td>{{ $member->section ?? '-' }}</td>
                                <td>
                                    <span class="badge-status {{ $member->status === 'active' ? 'active' : ($member->status === 'pending' ? 'pending' : 'inactive') }}">
                                        {{ ucfirst($member->status ?? 'inactif') }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $member->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox me-2"></i> Aucun membre récent
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Événements à venir -->
    <div class="col-xl-6">
        <div class="activity-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-calendar-check"></i>
                    Événements à venir
                </h5>
                <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-primary">
                    Voir tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table table-dashboard mb-0">
                        <thead>
                            <tr>
                                <th>Événement</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingEventsList as $event)
                            <tr>
                                <td class="fw-medium">{{ $event->title }}</td>
                                <td class="text-muted">{{ $event->start_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge-status {{ $event->status === 'upcoming' ? 'active' : ($event->status === 'planned' ? 'pending' : 'inactive') }}">
                                        {{ ucfirst($event->status ?? 'planifié') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox me-2"></i> Aucun événement à venir
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- STATISTIQUES RAPIDES                      -->
<!-- ========================================== -->
<div class="row g-4 mt-2">
    <div class="col-md-3">
        <div class="quick-card">
            <div class="quick-icon" style="background: #eef2ff; color: #3946B2;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <div class="quick-number">{{ number_format($upcomingEvents) }}</div>
                <div class="quick-label">Événements à venir</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="quick-card">
            <div class="quick-icon" style="background: #dcfce7; color: #22c55e;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="quick-number">{{ number_format($completedEvents) }}</div>
                <div class="quick-label">Événements terminés</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="quick-card">
            <div class="quick-icon" style="background: #fef3c7; color: #f59e0b;">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div>
                <div class="quick-number">{{ number_format($eventsThisMonth) }}</div>
                <div class="quick-label">Événements ce mois</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="quick-card">
            <div class="quick-icon" style="background: #fce4ec; color: #ec4899;">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <div class="quick-number">{{ number_format($newMembersThisMonth) }}</div>
                <div class="quick-label">Nouveaux membres</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // GRAPHIQUE DE RÉPARTITION DES SECTIONS
    // ==========================================
    const sectionCtx = document.getElementById('sectionChart').getContext('2d');
    const sectionData = @json($membersBySection);
    
    const colors = ['#3946B2', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'];
    
    if (sectionData.length > 0) {
        new Chart(sectionCtx, {
            type: 'doughnut',
            data: {
                labels: sectionData.map(d => d.section || 'Non défini'),
                datasets: [{
                    data: sectionData.map(d => d.total),
                    backgroundColor: colors.slice(0, sectionData.length),
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 16,
                            font: { size: 11, weight: '500' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' membres (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '70%',
            }
        });
    } else {
        sectionCtx.canvas.parentElement.innerHTML = 
            '<div class="text-center py-5 text-muted"><i class="fas fa-chart-pie fa-2x mb-2"></i><p>Aucune donnée disponible</p></div>';
    }

    // ==========================================
    // GRAPHIQUE D'ÉVOLUTION DES INSCRIPTIONS
    // ==========================================
    const membersCtx = document.getElementById('membersChart').getContext('2d');
    
    // Données mensuelles des inscriptions
    const monthlyData = @json($monthlyRegistrations ?? []);
    
    if (monthlyData.length > 0) {
        new Chart(membersCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: 'Nouveaux membres',
                    data: monthlyData.map(d => d.count),
                    backgroundColor: 'rgba(57, 70, 178, 0.1)',
                    borderColor: '#3946B2',
                    borderWidth: 3,
                    pointBackgroundColor: '#3946B2',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' nouveaux membres';
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
                            stepSize: Math.max(1, Math.ceil(Math.max(...monthlyData.map(d => d.count)) / 5))
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
    } else {
        membersCtx.canvas.parentElement.innerHTML = 
            '<div class="text-center py-5 text-muted"><i class="fas fa-chart-line fa-2x mb-2"></i><p>Aucune donnée disponible</p></div>';
    }
});
</script>
@endpush
@endsection
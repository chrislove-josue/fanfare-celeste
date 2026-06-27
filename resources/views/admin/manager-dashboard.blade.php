@extends('layouts.admin')

@section('title', 'Gestion des membres - Dashboard')

@section('page-title', 'Gestion des membres')
@section('page-subtitle', 'Gérez l\'ensemble des membres de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Membres</li>
@endsection

@section('content')
<style>
    /* ============================================
       STYLES PRO POUR LA GESTION DES MEMBRES
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
    
    /* === CARTE TABLEAU === */
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .table-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    .table-card .card-header-custom {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: transparent;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .table-card .card-header-custom h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .table-card .card-header-custom h5 i {
        color: #3946B2;
    }
    
    .table-card .card-body-custom {
        padding: 0;
        overflow-x: auto;
    }
    
    /* === TABLEAU === */
    .table-members {
        margin: 0;
        width: 100%;
        min-width: 600px;
    }
    
    .table-members thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 12px 16px;
        border-bottom: 2px solid #eef2f6;
        white-space: nowrap;
    }
    
    .table-members tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f3f5;
        color: #1a1a2e;
    }
    
    .table-members tbody tr {
        transition: background 0.2s ease;
        cursor: default;
    }
    
    .table-members tbody tr:hover {
        background: #f8fafc;
    }
    
    .table-members tbody tr:last-child td {
        border-bottom: none;
    }
    
    .table-members .member-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef2f6;
    }
    
    .table-members .member-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #eef2f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 600;
    }
    
    .table-members .member-name {
        font-weight: 500;
        color: #1a1a2e;
    }
    
    /* === BADGES === */
    .badge-status {
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
    
    .badge-status.suspended {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-section {
        background: #eef2ff;
        color: #3946B2;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
        white-space: nowrap;
    }
    
    /* === BOUTONS D'ACTION === */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    
    .action-buttons .btn-action {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        font-size: 0.75rem;
        text-decoration: none;
    }
    
    .action-buttons .btn-action.view {
        color: #3946B2;
        background: #eef2ff;
        border-color: #eef2ff;
    }
    
    .action-buttons .btn-action.view:hover {
        background: #3946B2;
        color: #fff;
        border-color: #3946B2;
    }
    
    .action-buttons .btn-action.edit {
        color: #f59e0b;
        background: #fef3c7;
        border-color: #fef3c7;
    }
    
    .action-buttons .btn-action.edit:hover {
        background: #f59e0b;
        color: #fff;
        border-color: #f59e0b;
    }
    
    .action-buttons .btn-action.delete {
        color: #dc2626;
        background: #fee2e2;
        border-color: #fee2e2;
    }
    
    .action-buttons .btn-action.delete:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }
    
    /* === CARTE GRAPHIQUE === */
    .chart-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    
    .chart-card h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .chart-card h5 i {
        color: #8b5cf6;
    }
    
    /* === CARTE ACTIONS RAPIDES === */
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
        .table-members {
            min-width: 500px;
        }
        .quick-actions-card {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .quick-actions-card .quick-buttons {
            justify-content: center;
        }
        .chart-card {
            margin-top: 1rem;
        }
    }
</style>

<!-- ========================================== -->
<!-- STATISTIQUES PRINCIPALES                    -->
<!-- ========================================== -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #3946B2; --stat-color-rgb: 57, 70, 178;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($totalMembers) }}</div>
                    <div class="stat-label">Total Membres</div>
                    <div class="mt-1">
                        <span class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +12% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #22c55e; --stat-color-rgb: 34, 197, 94;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($activeMembers) }}</div>
                    <div class="stat-label">Membres Actifs</div>
                    <div class="mt-1">
                        <span class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> +8% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #f59e0b; --stat-color-rgb: 245, 158, 11;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($pendingMembers) }}</div>
                    <div class="stat-label">En attente</div>
                    <div class="mt-1">
                        <span class="stat-trend down">
                            <i class="fas fa-arrow-down"></i> -3% ce mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-pro" style="--stat-color: #8b5cf6; --stat-color-rgb: 139, 92, 246;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrapper">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="stat-number">{{ number_format($newMembersThisMonth) }}</div>
                    <div class="stat-label">Nouveaux ce mois</div>
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
<!-- TABLEAU + GRAPHIQUE                        -->
<!-- ========================================== -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="table-card">
            <div class="card-header-custom">
                <h5>
                    <i class="fas fa-list-ul"></i>
                    Derniers Membres
                    <span class="badge bg-primary bg-opacity-10 text-primary ms-2 px-3 py-1" style="font-weight: 500; font-size: 0.7rem;">
                        {{ $recentMembers->count() }} récents
                    </span>
                </h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.members.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right me-1"></i> Voir tout
                    </a>
                </div>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table table-members">
                        <thead>
                            <tr>
                                <th>Membre</th>
                                <th>Section</th>
                                <th>Statut</th>
                                <th>Date d'adhésion</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($member->photo_url)
                                            <img src="{{ $member->photo_url }}" class="member-avatar" alt="{{ $member->name }}">
                                        @else
                                            <div class="member-avatar-placeholder">
                                                {{ strtoupper(substr($member->name ?? $member->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="member-name">{{ $member->name ?? $member->first_name . ' ' . $member->last_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-section">
                                        <i class="fas fa-users me-1" style="font-size: 0.5rem;"></i>
                                        {{ $member->section ?? 'Non défini' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status {{ $member->status === 'active' ? 'active' : ($member->status === 'pending' ? 'pending' : 'inactive') }}">
                                        <i class="fas fa-{{ $member->status === 'active' ? 'check-circle' : ($member->status === 'pending' ? 'clock' : 'times-circle') }}"></i>
                                        {{ ucfirst($member->status ?? 'inactif') }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $member->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="action-buttons justify-content-center">
                                        <a href="{{ route('admin.members.show', $member) }}" class="btn-action view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.members.edit', $member) }}" class="btn-action edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @can('delete members')
                                        <button type="button" class="btn-action delete" title="Supprimer" 
                                                onclick="confirmDelete('{{ $member->id }}', '{{ $member->name ?? $member->first_name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox me-2"></i> Aucun membre enregistré
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="chart-card">
            <h5>
                <i class="fas fa-pie-chart"></i>
                Répartition par section
                <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2 px-3 py-1" style="font-weight: 500; font-size: 0.7rem;">
                    {{ $totalMembers }} membres
                </span>
            </h5>
            <canvas id="sectionChart" height="280"></canvas>
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
                <p>Gérez les membres efficacement en un clic</p>
            </div>
            <div class="quick-buttons">
                <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Ajouter un membre
                </a>
                <a href="{{ route('admin.members.export') }}" class="btn btn-success">
                    <i class="fas fa-file-export me-1"></i> Exporter
                </a>
                <a href="#" class="btn btn-info text-white" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Imprimer
                </a>
                @if($pendingMembers > 0)
                    <a href="{{ route('admin.members.index', ['status' => 'pending']) }}" class="btn btn-warning">
                        <i class="fas fa-clock me-1"></i> En attente
                        <span class="badge bg-dark text-white ms-1">{{ $pendingMembers }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE CONFIRMATION SUPPRESSION          -->
<!-- ========================================== -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le membre <strong id="deleteMemberName"></strong> ?</p>
                <p class="text-muted small">Cette action est irréversible. Toutes les données liées à ce membre seront supprimées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ==========================================
    // GRAPHIQUE DE RÉPARTITION DES SECTIONS
    // ==========================================
    const sectionCtx = document.getElementById('sectionChart').getContext('2d');
    const sectionData = @json($membersBySection);
    const colors = ['#3946B2', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', '#14b8a6'];
    
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
                            padding: 14,
                            font: { size: 11, weight: '500' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' membres (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '70%',
            }
        });
    } else {
        sectionCtx.canvas.parentElement.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune donnée disponible</p>
            </div>
        `;
    }

    // ==========================================
    // CONFIRMATION DE SUPPRESSION
    // ==========================================
    function confirmDelete(id, name) {
        document.getElementById('deleteMemberName').textContent = name;
        document.getElementById('deleteForm').action = '{{ route("admin.members.index") }}/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
@endsection
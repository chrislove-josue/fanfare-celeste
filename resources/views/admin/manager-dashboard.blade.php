@extends('layouts.admin')

@section('title', 'Gestion des membres - Dashboard')

@section('page-title', 'Gestion des membres')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row g-4">
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
                    <div class="stat-number">{{ number_format($newMembersThisMonth) }}</div>
                    <div class="stat-label">Nouveaux ce mois</div>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #8B5CF6, #a78bfa);">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Derniers Membres</h5>
                <a href="{{ route('members.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Section</th>
                                <th>Statut</th>
                                <th>Date d'adhésion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMembers as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->section ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $member->status === 'active' ? 'success' : ($member->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($member->status ?? 'inactif') }}
                                    </span>
                                </td>
                                <td>{{ $member->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('members.show', $member) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun membre</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Actions rapides</h5>
                        <p class="text-muted small mb-0">Gérez les membres efficacement</p>
                    </div>
                    <div>
                        <a href="{{ route('members.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un membre
                        </a>
                        <a href="{{ route('members.export') }}" class="btn btn-success">
                            <i class="fas fa-file-export"></i> Exporter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
@extends('layouts.app')

@section('title', 'Rapport événement')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Rapport : {{ $event->title }}</h1>
            <div class="text-muted">
                {{ optional($event->start_date)->format('d/m/Y H:i') }}
            </div>
        </div>
        <div>
            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> Liste
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-uppercase small">Total</div>
                    <div class="fs-3 fw-bold">{{ $statistics['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
                <div class="card-body">
                    <div class="text-uppercase small">Présents</div>
                    <div class="fs-3 fw-bold">{{ $statistics['present'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <div class="card-body">
                    <div class="text-uppercase small">Absents</div>
                    <div class="fs-3 fw-bold">{{ $statistics['absent'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100" style="background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);">
                <div class="card-body">
                    <div class="text-uppercase small">Taux de présence</div>
                    <div class="fs-3 fw-bold">{{ $statistics['rate'] ?? 0 }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Détails des participants</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Numéro</th>
                            <th>Statut</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participations as $p)
                            <tr>
                                <td>{{ $p->member->full_name }}</td>
                                <td>{{ $p->member->member_number }}</td>
                                <td>
                                    @php
                                        $badgeMap = [
                                            'present' => 'success',
                                            'absent' => 'danger',
                                            'late' => 'warning',
                                            'excused' => 'secondary',
                                            'confirmed' => 'info',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badgeMap[$p->attendance_status] ?? 'secondary' }}">
                                        {{ ucfirst($p->attendance_status) }}
                                    </span>
                                </td>
                                <td>{{ $p->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune participation.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.admin')

@section('title', 'Mes événements')

@section('page-title', 'Mes événements')
@section('page-subtitle', 'Historique de vos participations')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('my.profile') }}">Mon profil</a></li>
    <li class="breadcrumb-item active">Événements</li>
@endsection

@section('content')
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-primary rounded-circle p-2 text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total participations</div>
                    <div class="h5 mb-0">{{ $totalEvents ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-success rounded-circle p-2 text-white">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">À venir</div>
                    <div class="h5 mb-0">{{ $upcomingEvents ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-info rounded-circle p-2 text-white">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total événements</div>
                    <div class="h5 mb-0">{{ $events->total() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>
            Mes participations
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Événement</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Présence</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $participation)
                    <tr>
                        <td>
                            <strong>{{ $participation->event->title ?? '-' }}</strong>
                        </td>
                        <td>
                            @if($participation->event)
                                {{ $participation->event->start_date?->format('d/m/Y H:i') ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $participation->event->location ?? '-' }}</td>
                        <td>
                            @if($participation->event)
                                @if($participation->event->status === 'upcoming')
                                    <span class="badge bg-primary">À venir</span>
                                @elseif($participation->event->status === 'ongoing')
                                    <span class="badge bg-success">En cours</span>
                                @elseif($participation->event->status === 'completed')
                                    <span class="badge bg-secondary">Terminé</span>
                                @else
                                    <span class="badge bg-warning">{{ $participation->event->status }}</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @if($participation->attendance_status === 'present')
                                <span class="badge bg-success">Présent</span>
                            @elseif($participation->attendance_status === 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @elseif($participation->attendance_status === 'late')
                                <span class="badge bg-warning">En retard</span>
                            @elseif($participation->attendance_status === 'excused')
                                <span class="badge bg-info">Excusé</span>
                            @else
                                <span class="badge bg-secondary">{{ $participation->attendance_status ?? 'Confirmé' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                            Aucune participation enregistrée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
            <div class="p-3">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
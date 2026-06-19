@extends('layouts.app')

@section('title', 'Rapport événement')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Rapport : {{ $event->title }}</h1>
        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Total</strong><div>{{ $statistics['total'] ?? 0 }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Présents</strong><div>{{ $statistics['present'] ?? 0 }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Absents</strong><div>{{ $statistics['absent'] ?? 0 }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Taux présence</strong><div>{{ $statistics['rate'] ?? 0 }}%</div></div></div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header"><strong>Détails participants</strong></div>
        <div class="card-body">
            @if($participations && $participations->count())
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Statut</th>
                            <th>Arrivée</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($participations as $p)
                            <tr>
                                <td>{{ $p->member->full_name ?? ($p->member->first_name.' '.$p->member->last_name) }}</td>
                                <td>{{ $p->attendance_status }}</td>
                                <td>{{ optional($p->arrival_time)->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Aucune participation.</p>
            @endif
        </div>
    </div>
</div>
@endsection


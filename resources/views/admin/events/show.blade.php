@extends('layouts.admin')

@section('title', $event->title)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ $event->title }}</h1>
            <div class="text-muted">
                {{ optional($event->start_date)->format('d/m/Y H:i') }}
                @if($event->end_date)
                    - {{ optional($event->end_date)->format('d/m/Y H:i') }}
                @endif
            </div>
        </div>

        <div>
            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.events.calendar') }}" class="btn btn-secondary">
                <i class="fas fa-calendar-alt"></i> Calendrier
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <strong>Détails</strong>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Type :</strong> {{ $event->type ?? '-' }}</p>
                    <p class="mb-1"><strong>Statut :</strong> {{ $event->status ?? '-' }}</p>
                    <p class="mb-1"><strong>Lieu :</strong> {{ $event->location ?? '-' }}</p>
                    @if(!empty($event->location_url))
                        <p class="mb-0">
                            <strong>URL :</strong>
                            <a href="{{ $event->location_url }}" target="_blank" rel="noopener">Ouvrir</a>
                        </p>
                    @endif
                    @if(!empty($event->poster_path))
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $event->poster_path) }}" class="img-fluid rounded" alt="Poster">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Participants</strong>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-primary">Total : {{ $totalParticipants ?? 0 }}</span>
                            <span class="badge bg-success">Présents : {{ $presentCount }}</span>
                            <span class="badge bg-danger">Absents : {{ $absentCount }}</span>
                            <span class="badge bg-warning">Retard : {{ $lateCount }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.events.add-participant', $event) }}" class="mb-4">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Ajouter un membre</label>
                                <select name="member_id" class="form-select" required>
                                    <option value="">—</option>
                                    @foreach($availableMembers as $member)
                                        <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->member_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> Inscrire
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <a href="{{ route('admin.events.report', $event) }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-file-alt"></i> Rapport
                                </a>
                            </div>
                        </div>
                    </form>

                    @forelse($participants as $participation)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">{{ $participation->member->full_name }}</h5>
                                        <div class="text-muted">{{ $participation->member->member_number }}</div>
                                    </div>

                                    <span class="badge bg-{{ $participation->attendance_status === 'present' ? 'success' : ($participation->attendance_status === 'absent' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($participation->attendance_status) }}
                                    </span>
                                </div>

                                <div class="mt-3">
                                    <form method="POST" action="{{ route('admin.events.update-participation', [$event, $participation->id]) }}">
                                        @csrf
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Présence</label>
                                                <select name="attendance_status" class="form-select" required>
                                                    @foreach(['confirmed','present','absent','late','excused'] as $status)
                                                        <option value="{{ $status }}" {{ $participation->attendance_status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Notes</label>
                                                <input type="text" name="notes" class="form-control" value="{{ $participation->notes }}" placeholder="Optionnel">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-save"></i> Mettre à jour
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">Aucun participant.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $event->title }}</h1>
        <div class="text-muted small mt-1">
            <i class="fas fa-users me-1"></i>
            Nombre de participants (membres du club + inscriptions publiques) : {{ $totalParticipants ?? 0 }}
        </div>
        <div>
            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-2">
                        <i class="far fa-calendar-alt"></i>
                        {{ optional($event->start_date)->format('d/m/Y H:i') }}
                        @if($event->end_date)
                            <span class="text-muted">→ {{ optional($event->end_date)->format('d/m/Y H:i') }}</span>
                        @endif
                    </p>
                    <p class="mb-2"><strong>Lieu :</strong> {{ $event->location }}</p>
                    <hr>
                    <div>{!! nl2br(e($event->description)) !!}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Participation</h5>
                    <div class="mb-2"><strong>Présents :</strong> {{ $presentCount }}</div>
                    <div class="mb-2"><strong>Absents :</strong> {{ $absentCount }}</div>
                    <div class="mb-2"><strong>En retard :</strong> {{ $lateCount }}</div>

                    <hr>

                    <h6 class="mb-2">Ajouter un participant</h6>
                    <form method="POST" action="{{ route('admin.events.add-participant', $event) }}">
                        @csrf
                        <select name="member_id" class="form-select mb-2" required>
                            @foreach($availableMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name ?? ($member->first_name.' '.$member->last_name) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary w-100" type="submit">Inscrire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header"><strong>Participants</strong></div>
        <div class="card-body">
            @if($participants && $participants->count())
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Statut</th>
                            <th>Notes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($participants as $p)
                            <tr>
                                <td>{{ $p->member->full_name ?? ($p->member->first_name.' '.$p->member->last_name) }}</td>
                                <td>{{ $p->attendance_status }}</td>
                                <td>{{ $p->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Aucun participant.</p>
            @endif
        </div>
    </div>
</div>
@endsection


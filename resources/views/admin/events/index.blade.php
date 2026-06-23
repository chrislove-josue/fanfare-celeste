@extends('layouts.admin')

@section('title', 'Gestion des événements')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des événements</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvel événement
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Événements à venir</strong></div>
        <div class="card-body">
            @if($upcomingEvents->count())
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($upcomingEvents as $event)
                            <tr>
                                <td>{{ $event->title }}</td>
                                <td>{{ optional($event->start_date)->format('d/m/Y H:i') }}</td>
                                <td>{{ $event->location }}</td>
                                <td>{{ $event->status }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('admin.events.show', $event) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.events.edit', $event) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $upcomingEvents->links() }}
            @else
                <p class="text-muted mb-0">Aucun événement à venir.</p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Événements passés</strong></div>
        <div class="card-body">
            @if($pastEvents->count())
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pastEvents as $event)
                            <tr>
                                <td>{{ $event->title }}</td>
                                <td>{{ optional($event->start_date)->format('d/m/Y H:i') }}</td>
                                <td>{{ $event->location }}</td>
                                <td>{{ $event->status }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('admin.events.show', $event) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.events.edit', $event) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pastEvents->links() }}
            @else
                <p class="text-muted mb-0">Aucun événement passé.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Événements en cours</strong></div>
        <div class="card-body">
            @if($ongoingEvents && count($ongoingEvents))
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ongoingEvents as $event)
                            <tr>
                                <td>{{ $event->title }}</td>
                                <td>{{ optional($event->start_date)->format('d/m/Y H:i') }}</td>
                                <td>{{ $event->location }}</td>
                                <td>{{ $event->status }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('admin.events.show', $event) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-warning btn-sm" href="{{ route('admin.events.edit', $event) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Aucun événement en cours.</p>
            @endif
        </div>
    </div>
</div>
@endsection


@extends('layouts.admin')

@section('title', 'Calendrier événements')

@section('content')
<div class="container py-4">
    <h1>Calendrier des événements</h1>
    <p class="text-muted">Prochaines dates (par période).</p>

    @forelse($eventsByDate as $date => $events)
        <div class="card mb-3">
            <div class="card-header"><strong>{{ $date }}</strong></div>
            <div class="card-body">
                <ul class="mb-0">
                    @foreach($events as $event)
                        <li>
                            <a href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a>
                            <span class="text-muted">- {{ $event->location }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Aucun événement à afficher.</div>
    @endforelse
</div>
@endsection


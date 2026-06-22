@extends('layouts.app')

@section('title', $event->title . ' - Fanfare Céleste')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('public.events.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    @if(!empty($event->poster_url))
                        <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" style="height: 360px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    @endif

                    <div class="card-body p-4">
                        <div class="text-primary mb-2" style="font-size: 1.05rem;">
                            <i class="far fa-calendar-alt"></i>
                            {{ optional($event->start_date)->format('d/m/Y H:i') }}
                            @if($event->end_date)
                                <span class="text-muted">→ {{ optional($event->end_date)->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>

                        <h1 class="mb-3" style="font-size: 2rem; line-height: 1.2;">{{ $event->title }}</h1>

                        

                        <div class="mb-4">
                            {!! nl2br(e($event->description)) !!}
                        </div>

                        @if(!empty($event->notes))
                            <div class="alert alert-secondary">
                                <strong>Notes</strong>
                                <div class="mt-2">{!! nl2br(e($event->notes)) !!}</div>
                            </div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <a href="{{ route('public.events.participate.form', ['event' => $event->id]) }}" class="btn btn-primary me-2">
    <i class="fas fa-user-plus"></i> S’inscrire à l’événement
</a>
                            <a href="{{ route('public.events.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-alt"></i> Tous les événements
                            </a>

                            <div class="text-muted">
                                <small>
                                    <i class="fas fa-users me-1"></i>
                                    Total participants inscrits: {{ $totalParticipants ?? 0 }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i> Informations</h5>

                        <div class="mb-3">
                        <h1 class="mb-3" style="font-size: 2rem; line-height: 1.2;">{{ $event->title }}</h1>

                            <div class="text-muted">Type</div>
                            <div class="fw-semibold">{{ \App\Models\Event::getTypes()[$event->type] ?? $event->type }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted">Statut</div>
                            <div class="fw-semibold">{{ \App\Models\Event::getStatuses()[$event->status] ?? $event->status }}</div>
                        </div>

                         <div class="text-primary mb-2" style="font-size: 1.05rem;">
                            <i class="far fa-calendar-alt"></i>
                            {{ optional($event->start_date)->format('d/m/Y H:i') }}
                            @if($event->end_date)
                                <span class="text-muted">→ {{ optional($event->end_date)->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>


                        
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $event->location }}
                            </div>
                            @if(!empty($event->location_url))
                                <div class="d-flex align-items-center">
                                    <a href="{{ $event->location_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-location-dot me-1"></i> Lien lieu
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info mt-4 mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Pour participer, cliquez sur « S’inscrire à l’événement ».
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


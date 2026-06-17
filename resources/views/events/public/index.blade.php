@extends('layouts.app')

@section('title', 'Événements - Fanfare Céleste')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Événements</h1>
                <p class="text-muted mb-0">Découvrez nos événements, sous forme de blog.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                    <div class="card h-100">
                        @if(!empty($event->poster_url))
                            <img src="{{ $event->poster_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 210px; object-fit: cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 210px; background: #f3f4f6; color:#6b7280;">
                                <i class="fas fa-calendar-alt" style="font-size: 2rem;"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <div class="text-primary mb-2" style="font-size: 0.95rem;">
                                <i class="far fa-calendar-alt"></i>
                                {{ optional($event->start_date)->format('d/m/Y H:i') }}
                            </div>

                            <h5 class="card-title">{{ $event->title }}</h5>

                            <p class="card-text text-muted" style="line-height: 1.5;">
                                {{ 
                                    Str::limit($event->description, 130)
                                }}
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $event->location }}
                                    </small>
                                </div>
<a href="{{ route('public.events.show', $event) }}" class="stretched-link" style="text-decoration:none;">Lire l’article</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Aucun événement trouvé.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>
</section>
@endsection

@push('styles')
    <style>
        .card-img-top { border-top-left-radius: 15px; border-top-right-radius: 15px; }
        .card { border-radius: 15px; }
    </style>
@endpush


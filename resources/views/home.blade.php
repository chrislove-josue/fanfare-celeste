@extends('layouts.app')

@section('title', 'Accueil - Fanfare Céleste')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #1a237e, #283593); color: white; padding: 100px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fade-in-up">
                <h1 class="display-4 mb-4">Fanfare Céleste<br>La Trompette de Dieu de l'Atlantique</h1>
                <p class="lead mb-4">Une fanfare au service de Dieu, animée par la passion de la musique et l'excellence.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('membership.apply') }}" class="btn btn-light btn-lg">Adhérer maintenant</a>
                    <a href="{{ url('/events') }}" class="btn btn-outline-light btn-lg">Nos événements</a>

                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" class="img-fluid" style="max-height: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Statistiques -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="stat-card text-center">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h3 class="counter">150</h3>
                    <p>Membres Actifs</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card text-center">
                    <i class="fas fa-music fa-3x mb-3"></i>
                    <h3 class="counter">8</h3>
                    <p>Sections Musicales</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card text-center">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <h3 class="counter">50</h3>
                    <p>Concerts par an</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card text-center">
                    <i class="fas fa-trophy fa-3x mb-3"></i>
                    <h3 class="counter">15</h3>
                    <p>Distinctions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Événements à venir -->
<section class="events-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Événements à venir</h2>
        <div class="row">
            @foreach($upcomingEvents ?? [] as $event)
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="event-date text-primary mb-2">
                            <i class="far fa-calendar-alt"></i> {{ $event->start_date->format('d/m/Y H:i') }}
                        </div>
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                        <div class="event-location mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $event->location }}
                        </div>
                        <div class="event-participants text-muted mb-3">
                            <i class="fas fa-users me-1"></i>
                            Participants : {{ $event->totalParticipants ?? ($event->members_total ?? 0) }}
                        </div>
                        <a href="{{ route('public.events.show', $event) }}" class="btn btn-primary">En savoir plus</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Témoignages -->
<section class="testimonials-section py-5">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Ce qu'ils disent de nous</h2>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                        <p class="card-text">"Une fanfare exceptionnelle qui apporte joie et spiritualité à travers leur musique."</p>
                        <h6 class="mt-3">- Pasteur Jean Claude</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                        <p class="card-text">"Professionnalisme et excellence dans chaque prestation. Une fierté nationale."</p>
                        <h6 class="mt-3">- Ministère de la Culture</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                        <p class="card-text">"Formidable organisation, discipline et talent. Une référence dans le domaine."</p>
                        <h6 class="mt-3">- Association des Fanfares</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Galerie -->
<section class="gallery-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">Galerie</h2>
        <div class="row g-3">
            @foreach($recentGalleries ?? [] as $gallery)
            <div class="col-md-3" data-aos="fade-up">
                <div class="gallery-item position-relative overflow-hidden rounded">
                    @if($gallery->type == 'photo')
                        <img src="{{ Storage::url($gallery->file_path) }}" class="img-fluid" style="height: 250px; width: 100%; object-fit: cover;">
                    @else
                        <video src="{{ Storage::url($gallery->file_path) }}" class="img-fluid" style="height: 250px; width: 100%; object-fit: cover;"></video>
                    @endif
                    <div class="overlay position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2">
                        <small>{{ $gallery->title }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ url('/admin/gallery') }}" class="btn btn-primary">Voir toute la galerie</a>

        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container text-center">
        <h3 data-aos="fade-up">Restez informé</h3>
        <p class="mb-4" data-aos="fade-up">Abonnez-vous à notre newsletter pour recevoir nos actualités</p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="row justify-content-center" data-aos="fade-up">
            @csrf
            <div class="col-md-6">
                <div class="input-group">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Votre email" required>
                    <button type="submit" class="btn btn-light btn-lg">S'abonner</button>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const updateCount = () => {
            const target = parseInt(counter.innerText);
            const count = parseInt(counter.innerText);
            const increment = target / 200;
            
            if(count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 10);
            } else {
                counter.innerText = target;
            }
        }
        
        const observer = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting) {
                updateCount();
                observer.disconnect();
            }
        });
        
        observer.observe(counter);
    });
</script>
@endpush
@endsection
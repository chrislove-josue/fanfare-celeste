@extends('layouts.app')

@section('title', 'Accueil - Fanfare Céleste')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #1a237e, #283593); color: white; padding: 100px 0; position: relative; overflow: hidden;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <!-- Slide - En haut sur mobile, à droite sur desktop -->
            <div class="col-lg-6 order-first order-lg-last" data-aos="fade-left">
                <div class="hero-slider-wrapper" style="position: relative; overflow: hidden; border-radius: 12px; background: rgba(255,255,255,0.05); padding: 20px 0;">
                    <div class="hero-slider" style="display: flex; animation: slideHero 15s linear infinite; width: max-content;">
                        <!-- Première série -->
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <!-- Deuxième série pour l'effet infini -->
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                        <div class="hero-slide-item" style="min-width: 280px; padding: 0 15px; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Fanfare Céleste" style="max-height: 250px; width: auto; filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));">
                        </div>
                    </div>
                    
                    <!-- Gradient overlay -->
                    <div style="position: absolute; top: 0; left: 0; width: 60px; height: 100%; background: linear-gradient(to right, #1a237e, transparent); pointer-events: none; z-index: 2;"></div>
                    <div style="position: absolute; top: 0; right: 0; width: 60px; height: 100%; background: linear-gradient(to left, #1a237e, transparent); pointer-events: none; z-index: 2;"></div>
                </div>
            </div>
            
            <!-- Texte - En bas sur mobile, à gauche sur desktop -->
            <div class="col-lg-6 order-last order-lg-first fade-in-up">
                <div class="mb-3">
                    <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.9rem;">
                        <i class="fas fa-music me-2"></i> Événement à venir
                    </span>
                </div>
                <h1 class="display-4 mb-4 fw-bold">Fanfare Céleste<br>La Trompette de Dieu de l'Atlantique</h1>
                <p class="lead mb-4">Une fanfare au service de Dieu, animée par la passion de la musique et l'excellence. Rejoignez-nous pour vivre une expérience musicale unique.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('membership.apply') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-hand-peace me-2"></i> Adhérer maintenant
                    </a>
                    <a href="{{ route('public.events.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-calendar-alt me-2"></i> Nos événements
                    </a>
                    <a href="{{ route('public.gallery.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-images me-2"></i> Galerie
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -150px; left: -150px; width: 500px; height: 500px; background: rgba(255,255,255,0.02); border-radius: 50%;"></div>
</section>

<!-- Statistiques -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6" data-aos="fade-up">
                <div class="stat-card text-center p-4 bg-white rounded shadow-sm">
                    <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">{{ $memberCount ?? 0 }}</h3>
                    <p class="text-muted mb-0">Membres Actifs</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card text-center p-4 bg-white rounded shadow-sm">
                    <i class="fas fa-music fa-3x mb-3 text-success"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">8</h3>
                    <p class="text-muted mb-0">Sections Musicales</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card text-center p-4 bg-white rounded shadow-sm">
                    <i class="fas fa-calendar-check fa-3x mb-3 text-warning"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">{{ $eventCount ?? 0 }}</h3>
                    <p class="text-muted mb-0">Concerts réalisés</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card text-center p-4 bg-white rounded shadow-sm">
                    <i class="fas fa-trophy fa-3x mb-3 text-danger"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">{{ $achievements ?? 0 }}</h3>
                    <p class="text-muted mb-0">Distinctions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- À propos en bref -->
<section class="about-brief py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="fw-bold mb-3">Une fanfare d'excellence</h2>
                <p class="lead">La Fanfare Céleste est bien plus qu'une simple fanfare. C'est une famille unie par la passion de la musique et le service de Dieu.</p>
                <div class="row mt-4">
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <div>
                                <h6 class="fw-bold">Formation musicale</h6>
                                <p class="text-muted small">Des cours pour tous les niveaux</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <div>
                                <h6 class="fw-bold">Concerts et événements</h6>
                                <p class="text-muted small">Plus de 50 concerts par an</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <div>
                                <h6 class="fw-bold">Excellence musicale</h6>
                                <p class="text-muted small">Des musiciens talentueux</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <div>
                                <h6 class="fw-bold">Vie spirituelle</h6>
                                <p class="text-muted small">Au service de Dieu et de la communauté</p>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-info-circle me-2"></i> En savoir plus
                </a>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1507838153414-b4b713384a76?w=800&h=600&fit=crop" alt="Fanfare" class="img-fluid rounded shadow" style="height: 400px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Événements à venir -->
<section class="events-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <h2 class="fw-bold mb-0">Événements à venir</h2>
                <p class="text-muted">Découvrez nos prochains concerts et manifestations</p>
            </div>
            <a href="{{ route('public.events.index') }}" class="btn btn-primary">
                <i class="fas fa-calendar-alt me-2"></i> Voir tous les événements
            </a>
        </div>

        @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
        <div class="row g-4">
            @foreach($upcomingEvents as $event)
            <div class="col-md-4 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="card h-100 shadow-sm hover-shadow transition">
                    <div class="position-relative">
                        @if($event->featured_image)
                            <img src="{{ asset('storage/' . $event->featured_image) }}" 
                                 alt="{{ $event->title }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-alt fa-4x text-white opacity-50"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary px-3 py-2">
                                <i class="fas fa-clock me-1"></i> 
                                {{ $event->start_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="event-date text-muted small mb-2">
                            <i class="far fa-calendar-alt text-primary me-1"></i> 
                            {{ $event->start_date->format('d/m/Y à H:i') }}
                        </div>
                        <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($event->description, 80) }}</p>
                        <div class="event-location mb-2">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i> 
                            {{ $event->location }}
                        </div>
                        <div class="event-participants text-muted small">
                            <i class="fas fa-users me-1"></i>
                            Participants : {{ $event->totalParticipants ?? ($event->members_total ?? 0) }}
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('public.events.show', $event) }}" class="btn btn-primary w-100">
                            <i class="fas fa-info-circle me-2"></i> En savoir plus
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('public.events.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-calendar-alt me-2"></i> Voir tous les événements
            </a>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <h4>Aucun événement à venir</h4>
            <p class="text-muted">Revenez bientôt pour découvrir nos prochains événements.</p>
        </div>
        @endif
    </div>
</section>

<!-- Témoignages -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-2" data-aos="fade-up">Ce qu'ils disent de nous</h2>
        <p class="text-center text-muted mb-5" data-aos="fade-up">Les témoignages de ceux qui nous connaissent</p>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                        <p class="card-text font-italic">"Une fanfare exceptionnelle qui apporte joie et spiritualité à travers leur musique. Chaque prestation est un moment de grâce."</p>
                        <div class="mt-3">
                            <div class="text-warning mb-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="mb-0">- Pasteur Jean Claude</h6>
                            <small class="text-muted">Église Évangélique</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                        <p class="card-text font-italic">"Professionnalisme et excellence dans chaque prestation. Une fierté nationale qui rayonne au-delà de nos frontières."</p>
                        <div class="mt-3">
                            <div class="text-warning mb-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="mb-0">- Ministère de la Culture</h6>
                            <small class="text-muted">République du Bénin</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                        <p class="card-text font-italic">"Formidable organisation, discipline et talent. Une référence dans le domaine musical au Bénin et en Afrique."</p>
                        <div class="mt-3">
                            <div class="text-warning mb-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="mb-0">- Association des Fanfares</h6>
                            <small class="text-muted">Afrique de l'Ouest</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Galerie -->
<section class="gallery-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div>
                <h2 class="fw-bold mb-0">Galerie</h2>
                <p class="text-muted">Découvrez nos moments forts en images</p>
            </div>
            <a href="{{ route('public.gallery.index') }}" class="btn btn-primary">
                <i class="fas fa-images me-2"></i> Voir toute la galerie
            </a>
        </div>

        @if(isset($recentGalleries) && $recentGalleries->count() > 0)
        <div class="row g-3">
            @foreach($recentGalleries as $gallery)
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('public.gallery.show', $gallery) }}" class="text-decoration-none">
                    <div class="gallery-item position-relative overflow-hidden rounded shadow-sm">
                        @if($gallery->cover_image_url)
                            <img src="{{ $gallery->cover_image_url }}" 
                                 alt="{{ $gallery->title }}" 
                                 style="height: 200px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
                        @else
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-images fa-3x text-white opacity-50"></i>
                            </div>
                        @endif
                        <div class="overlay position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2">
                            <small class="d-block text-truncate">
                                <i class="fas fa-images me-1"></i>
                                {{ Str::limit($gallery->title, 30) }}
                            </small>
                            <small class="d-block">
                                <i class="fas fa-photo-video me-1"></i>
                                {{ $gallery->media_count ?? 0 }} médias
                            </small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('public.gallery.index') }}" class="btn btn-primary">
                <i class="fas fa-images me-2"></i> Voir toute la galerie
            </a>
        </div>
        @elseif(isset($recentMedia) && $recentMedia->count() > 0)
        <div class="row g-3">
            @foreach($recentMedia as $media)
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="gallery-item position-relative overflow-hidden rounded shadow-sm">
                    @if($media->type == 'photo')
                        <img src="{{ $media->file_url }}" 
                             alt="{{ $media->title }}" 
                             style="height: 200px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
                    @else
                        <video src="{{ $media->file_url }}" 
                               style="height: 200px; width: 100%; object-fit: cover;"
                               muted></video>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-2x text-white opacity-75"></i>
                        </div>
                    @endif
                    <div class="overlay position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2">
                        <small class="d-block">
                            <i class="fas fa-{{ $media->type == 'photo' ? 'image' : 'video' }} me-1"></i>
                            {{ Str::limit($media->title, 30) }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <h4>Aucune image dans la galerie</h4>
            <p class="text-muted">Revenez bientôt pour découvrir nos photos.</p>
        </div>
        @endif
    </div>
</section>

<!-- Sections -->
<section class="sections-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-2" data-aos="fade-up">Nos sections</h2>
        <p class="text-center text-muted mb-5" data-aos="fade-up">Découvrez les différentes familles d'instruments de notre fanfare</p>
        <div class="row g-4">
            <div class="col-md-3 col-6" data-aos="fade-up">
                <div class="text-center p-3 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-trumpet fa-3x text-primary mb-2"></i>
                    <h6 class="fw-bold">Trompettes</h6>
                    <small class="text-muted">25 musiciens</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="50">
                <div class="text-center p-3 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-music fa-3x text-success mb-2"></i>
                    <h6 class="fw-bold">Trombones</h6>
                    <small class="text-muted">15 musiciens</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center p-3 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-saxophone fa-3x text-warning mb-2"></i>
                    <h6 class="fw-bold">Saxophones</h6>
                    <small class="text-muted">18 musiciens</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="150">
                <div class="text-center p-3 bg-white rounded shadow-sm h-100">
                    <i class="fas fa-drum fa-3x text-danger mb-2"></i>
                    <h6 class="fw-bold">Percussions</h6>
                    <small class="text-muted">20 musiciens</small>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('about') }}" class="btn btn-primary">
                <i class="fas fa-info-circle me-2"></i> En savoir plus sur nos sections
            </a>
        </div>
    </div>
</section>

<!-- Partenaires - Slide Infini -->
<section class="partners-section py-5" style="background: #f8f9fa; overflow: hidden;">
    <div class="container">
        <h2 class="text-center fw-bold mb-2" data-aos="fade-up">Nos Partenaires</h2>
        <p class="text-center text-muted mb-5" data-aos="fade-up">Ils nous accompagnent dans notre mission</p>
        
        @if(isset($partners) && $partners->count() > 0)
        <div class="partner-slider-wrapper" style="position: relative; overflow: hidden;">
            <div class="partner-slider" style="display: flex; animation: slidePartners 20s linear infinite; width: max-content;">
                <!-- Première série -->
                @foreach($partners as $partner)
                <div class="partner-item text-center" style="min-width: 150px; padding: 0 15px;">
                    <div class="p-3 bg-white rounded shadow-sm hover-shadow transition" style="height: 100px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        @if($partner->logo_url)
                            <img src="{{ $partner->logo_url }}" 
                                 alt="{{ $partner->name }}" 
                                 class="img-fluid" 
                                 style="max-height: 50px; object-fit: contain; filter: grayscale(0); transition: filter 0.3s ease;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 50px;">
                                <i class="fas fa-building fa-2x text-muted"></i>
                            </div>
                        @endif
                        <small class="d-block mt-1 text-muted" style="font-size: 0.7rem;">{{ Str::limit($partner->name, 20) }}</small>
                    </div>
                </div>
                @endforeach
                <!-- Deuxième série pour l'effet infini -->
                @foreach($partners as $partner)
                <div class="partner-item text-center" style="min-width: 150px; padding: 0 15px;">
                    <div class="p-3 bg-white rounded shadow-sm hover-shadow transition" style="height: 100px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        @if($partner->logo_url)
                            <img src="{{ $partner->logo_url }}" 
                                 alt="{{ $partner->name }}" 
                                 class="img-fluid" 
                                 style="max-height: 50px; object-fit: contain; filter: grayscale(0); transition: filter 0.3s ease;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 50px;">
                                <i class="fas fa-building fa-2x text-muted"></i>
                            </div>
                        @endif
                        <small class="d-block mt-1 text-muted" style="font-size: 0.7rem;">{{ Str::limit($partner->name, 20) }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Gradient overlay pour l'effet de fondu -->
            <div style="position: absolute; top: 0; left: 0; width: 80px; height: 100%; background: linear-gradient(to right, #f8f9fa, transparent); pointer-events: none; z-index: 2;"></div>
            <div style="position: absolute; top: 0; right: 0; width: 80px; height: 100%; background: linear-gradient(to left, #f8f9fa, transparent); pointer-events: none; z-index: 2;"></div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('about') }}#partenaires" class="btn btn-primary">
                <i class="fas fa-handshake me-2"></i> Voir tous nos partenaires
            </a>
        </div>
        @else
        <div class="text-center py-4">
            <p class="text-muted">Nos partenaires seront bientôt disponibles.</p>
        </div>
        @endif
    </div>
</section>

<!-- Appel à l'action -->
<section class="cta-section py-5 my-10" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Rejoignez la Fanfare Céleste</h2>
        <p class="lead mb-4">Vous êtes musicien ou vous souhaitez apprendre ? Rejoignez notre grande famille !</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('membership.apply') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-hand-peace me-2"></i> Adhérer maintenant
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5">
                <i class="fas fa-envelope me-2"></i> Nous contacter
            </a>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container text-center">
        <h3 class="fw-bold">Restez informé</h3>
        <p class="mb-4">Abonnez-vous à notre newsletter pour recevoir nos actualités et événements</p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="row justify-content-center">
            @csrf
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Votre email" required>
                    <button type="submit" class="btn btn-light btn-lg">
                        <i class="fas fa-paper-plane me-2"></i> S'abonner
                    </button>
                </div>
                <small class="d-block mt-2 opacity-75">Recevez nos actualités et ne manquez aucun événement</small>
            </div>
        </form>
    </div>
</section>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .gallery-item .overlay {
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    .gallery-item:hover .overlay {
        transform: translateY(0);
    }
    .gallery-item img,
    .gallery-item video {
        transition: transform 0.3s ease;
    }

    /* Animation slide infini des partenaires */
    @keyframes slidePartners {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    .partner-slider {
        display: flex;
        animation: slidePartners 25s linear infinite;
        width: max-content;
    }

    .partner-slider:hover {
        animation-play-state: paused;
    }

    .partner-item {
        flex-shrink: 0;
    }

    .partner-item img {
        transition: all 0.3s ease;
    }

    .partner-item:hover img {
        transform: scale(1.05);
        filter: brightness(0.9);
    }

    .partner-item .hover-shadow {
        transition: all 0.3s ease;
    }

    .partner-item .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }

    /* Animation slide infini du hero */
    @keyframes slideHero {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    .hero-slider {
        display: flex;
        animation: slideHero 12s linear infinite;
        width: max-content;
    }

    .hero-slider:hover {
        animation-play-state: paused;
    }

    .hero-slide-item {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-slide-item img {
        transition: all 0.3s ease;
    }

    .hero-slide-item:hover img {
        transform: scale(1.02);
        filter: brightness(1.1);
    }

    .hero-slider-wrapper {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 10px 0;
    }

    /* Ordre des colonnes sur mobile */
    @media (max-width: 991.98px) {
        .order-first {
            order: -1 !important;
        }
        .order-last {
            order: 1 !important;
        }
        .hero-slide-item img {
            max-height: 180px !important;
        }
        .hero-section {
            padding: 60px 0 !important;
        }
        .display-4 {
            font-size: 2.2rem !important;
        }
    }

    @media (max-width: 575.98px) {
        .hero-slide-item img {
            max-height: 150px !important;
        }
        .hero-slider-wrapper {
            padding: 10px 0;
        }
        .display-4 {
            font-size: 1.8rem !important;
        }
        .lead {
            font-size: 1rem !important;
        }
    }
</style>
@endpush

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

    // Hover effect sur la galerie
    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            const overlay = this.querySelector('.overlay');
            if (overlay) {
                overlay.style.transform = 'translateY(0)';
            }
            const img = this.querySelector('img, video');
            if (img) {
                img.style.transform = 'scale(1.05)';
            }
        });
        item.addEventListener('mouseleave', function() {
            const overlay = this.querySelector('.overlay');
            if (overlay) {
                overlay.style.transform = 'translateY(100%)';
            }
            const img = this.querySelector('img, video');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });

    // Pause du slide des partenaires au survol
    document.querySelector('.partner-slider-wrapper')?.addEventListener('mouseenter', function() {
        const slider = this.querySelector('.partner-slider');
        if (slider) {
            slider.style.animationPlayState = 'paused';
        }
    });

    document.querySelector('.partner-slider-wrapper')?.addEventListener('mouseleave', function() {
        const slider = this.querySelector('.partner-slider');
        if (slider) {
            slider.style.animationPlayState = 'running';
        }
    });

    // Pause du slide du hero au survol
    document.querySelector('.hero-slider-wrapper')?.addEventListener('mouseenter', function() {
        const slider = this.querySelector('.hero-slider');
        if (slider) {
            slider.style.animationPlayState = 'paused';
        }
    });

    document.querySelector('.hero-slider-wrapper')?.addEventListener('mouseleave', function() {
        const slider = this.querySelector('.hero-slider');
        if (slider) {
            slider.style.animationPlayState = 'running';
        }
    });
</script>
@endpush
@endsection
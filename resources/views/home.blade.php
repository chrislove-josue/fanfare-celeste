@extends('layouts.app')

@section('title', 'Accueil - Fanfare Céleste')

@section('content')
<!-- Hero Section -->
<section class="hero-section px-2" style="background: linear-gradient(135deg, #1a237e, #283593); min-height: 100vh; display: flex; align-items: center; position: relative; overflow: hidden;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <!-- ===== TEXTE - À GAUCHE ===== -->
            <div class="order-last col-lg-6 order-lg-first fade-in-up m-0">
                <div class="mb-3">
                    <span class="px-3 py-2 mt-2 badge bg-warning text-dark" style="font-size: 0.9rem;">
                        <i class="fas fa-music me-2"></i> Fanfare Céleste
                    </span>
                </div>
                <h1 class="mb-4 text-white display-4 fw-bold">Fanfare Céleste<br>La Trompette de Dieu de l'Atlantique</h1>
                <p class="mb-4 lead text-white-50">Une fanfare au service de Dieu, animée par la passion de la musique et l'excellence. Rejoignez-nous pour vivre une expérience musicale unique.</p>
                <div class="flex-wrap gap-3 d-flex">
                    <a href="{{ route('membership.apply') }}" class="px-4 btn btn-light btn-lg">
                        <i class="fas fa-hand-peace me-2"></i> Adhérer maintenant
                    </a>
                    <a href="{{ route('public.events.index') }}" class="px-4 btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar-alt me-2"></i> Nos événements
                    </a>
                    <a href="{{ route('public.gallery.index') }}" class="px-4 btn btn-outline-light btn-lg">
                        <i class="fas fa-images me-2"></i> Galerie
                    </a>
                </div>
            </div>

            <!-- ===== DROITE - CAROUSEL OU IMAGE STATIQUE ===== -->
            <div class="order-first col-lg-6 order-lg-last" data-aos="fade-left">
                @if(isset($carousels) && $carousels->count() > 0)
                    <!-- ===== CAROUSEL ===== -->
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000" style="border-radius: 12px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                        <div class="carousel-indicators">
                            @foreach($carousels as $index => $slide)
                                <button type="button" data-bs-target="#heroCarousel" 
                                        data-bs-slide-to="{{ $index }}" 
                                        class="{{ $index === 0 ? 'active' : '' }}" 
                                        aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($carousels as $index => $slide)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="carousel-slide" style="position: relative; height: 500px; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $slide->image) }}" 
                                             alt="{{ $slide->title ?? 'Slide' }}" 
                                             style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                        @if($slide->title || $slide->subtitle)
                                            <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 30px 20px; background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                @if($slide->title)
                                                    <h3 class="mb-1 text-white fw-bold">{{ $slide->title }}</h3>
                                                @endif
                                                @if($slide->subtitle)
                                                    <p class="mb-0 text-white-50">{{ $slide->subtitle }}</p>
                                                @endif
                                                @if($slide->link)
                                                    <a href="{{ $slide->link }}" class="mt-2 btn btn-sm btn-light">
                                                        {{ $slide->link_text ?? 'En savoir plus' }}
                                                        <i class="fas fa-arrow-right ms-1"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($carousels->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        @endif
                    </div>
                @else
                    <!-- ===== IMAGE STATIQUE ===== -->
                    <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3); height: 500px; position: relative;">
                        <img src="{{ asset('storage/img/logo.jpeg') }}" 
                             alt="Fanfare Céleste" 
                             style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 30px 20px; background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <h3 class="mb-1 text-white fw-bold">Fanfare Céleste</h3>
                            <p class="mb-0 text-white-50">La Trompette de Dieu de l'Atlantique</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Décorations -->
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -150px; left: -150px; width: 500px; height: 500px; background: rgba(255,255,255,0.02); border-radius: 50%;"></div>
</section>

<!-- Statistiques -->
<section class="py-5 stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6" data-aos="fade-up">
                <div class="p-4 text-center bg-white rounded shadow-sm stat-card">
                    <i class="mb-3 fas fa-users fa-3x text-primary"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">{{ $memberCount ?? 0 }}</h3>
                    <p class="mb-0 text-muted">Membres Actifs</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 text-center bg-white rounded shadow-sm stat-card">
                    <i class="mb-3 fas fa-music fa-3x text-success"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">8</h3>
                    <p class="mb-0 text-muted">Sections Musicales</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 text-center bg-white rounded shadow-sm stat-card">
                    <i class="mb-3 fas fa-calendar-check fa-3x text-warning"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">{{ $eventCount ?? 0 }}</h3>
                    <p class="mb-0 text-muted">Concerts réalisés</p>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                <div class="p-4 text-center bg-white rounded shadow-sm stat-card">
                    <i class="mb-3 fas fa-trophy fa-3x text-danger"></i>
                    <h3 class="counter fw-bold" style="font-size: 2.5rem;">{{ $achievements ?? 0 }}</h3>
                    <p class="mb-0 text-muted">Distinctions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- À propos en bref -->
<section class="py-5 about-brief">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="mb-3 fw-bold">Une fanfare d'excellence</h2>
                <p class="lead">La Fanfare Céleste est bien plus qu'une simple fanfare. C'est une famille unie par la passion de la musique et le service de Dieu.</p>
                <div class="mt-4 row">
                    <div class="mb-3 col-sm-6">
                        <div class="d-flex align-items-start">
                            <i class="mt-1 fas fa-check-circle text-success me-2"></i>
                            <div>
                                <h6 class="fw-bold">Formation musicale</h6>
                                <p class="text-muted small">Des cours pour tous les niveaux</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <div class="d-flex align-items-start">
                            <i class="mt-1 fas fa-check-circle text-success me-2"></i>
                            <div>
                                <h6 class="fw-bold">Concerts et événements</h6>
                                <p class="text-muted small">Plus de 50 concerts par an</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <div class="d-flex align-items-start">
                            <i class="mt-1 fas fa-check-circle text-success me-2"></i>
                            <div>
                                <h6 class="fw-bold">Excellence musicale</h6>
                                <p class="text-muted small">Des musiciens talentueux</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 col-sm-6">
                        <div class="d-flex align-items-start">
                            <i class="mt-1 fas fa-check-circle text-success me-2"></i>
                            <div>
                                <h6 class="fw-bold">Vie spirituelle</h6>
                                <p class="text-muted small">Au service de Dieu et de la communauté</p>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="mt-2 btn btn-primary">
                    <i class="fas fa-info-circle me-2"></i> En savoir plus
                </a>
            </div>
            <div class="mt-4 col-lg-6 mt-lg-0" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1507838153414-b4b713384a76?w=800&h=600&fit=crop" alt="Fanfare" class="rounded shadow img-fluid" style="height: 400px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!-- SECTION ÉVÉNEMENTS À VENIR -->
<!-- ============================================ -->
<section class="py-5 events-section bg-light">
    <div class="container">
        <div class="mb-5 d-flex justify-content-between align-items-center" data-aos="fade-up">
            <div>
                <h2 class="mb-0 fw-bold">Événements à venir</h2>
                <p class="text-muted">Découvrez nos prochains concerts et manifestations</p>
            </div>
            <a href="{{ route('public.events.index') }}" class="btn btn-primary">
                <i class="fas fa-calendar-alt me-2"></i> Voir tous les événements
            </a>
        </div>

        @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
        <div class="row g-4">
            @foreach($upcomingEvents as $event)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="transition shadow-sm card h-100 hover-shadow">
                    <div class="position-relative">
                        @if($event->poster_path)
                            <img src="{{ asset('storage/' . $event->poster_path) }}" 
                                 alt="{{ $event->title }}" 
                                 class="card-img-top" 
                                 style="height: 200px; width: 100%; object-fit: cover;">
                        @else
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="text-white opacity-50 fas fa-calendar-alt fa-4x"></i>
                            </div>
                        @endif
                        <div class="top-0 m-2 position-absolute end-0">
                            <span class="px-3 py-2 badge bg-primary">
                                <i class="fas fa-clock me-1"></i> 
                                {{ $event->start_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 text-muted small">
                            <i class="far fa-calendar-alt text-primary me-1"></i> 
                            {{ $event->start_date->format('d/m/Y à H:i') }}
                        </div>
                        <h5 class="card-title fw-bold">{{ $event->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($event->description, 80) }}</p>
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i> 
                            {{ $event->location }}
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-users me-1"></i>
                            Participants : {{ $event->totalParticipants ?? 0 }}
                        </div>
                    </div>
                    <div class="pt-0 bg-transparent border-0 card-footer">
                        <a href="{{ route('public.events.show', $event) }}" class="btn btn-primary w-100">
                            <i class="fas fa-info-circle me-2"></i> En savoir plus
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-5 text-center">
            <i class="mb-3 fas fa-calendar-times fa-4x text-muted"></i>
            <h4>Aucun événement à venir</h4>
            <p class="text-muted">Revenez bientôt pour découvrir nos prochains événements.</p>
        </div>
        @endif
    </div>
</section>


<!-- ============================================ -->
<!-- SECTION ACTUALITÉS -->
<!-- ============================================ -->
<section class="py-5 news-section">
    <div class="container">
        <div class="mb-5 d-flex justify-content-between align-items-center" data-aos="fade-up">
            <div>
                <h2 class="mb-0 fw-bold">Dernières actualités</h2>
                <p class="text-muted">Restez informé des dernières nouvelles de la fanfare</p>
            </div>
            <a href="{{ route('public.news.index') }}" class="btn btn-primary">
                <i class="fas fa-newspaper me-2"></i> Voir toutes les actualités
            </a>
        </div>

        @if(isset($latestNews) && $latestNews->count() > 0)
        <div class="row g-4">
            @foreach($latestNews as $news)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="transition shadow-sm card h-100 hover-shadow">
                    @if($news->cover_image_path)
                        <img src="{{ asset('storage/' . $news->cover_image_path) }}" 
                             alt="{{ $news->title }}" 
                             class="card-img-top" 
                             style="height: 200px; width: 100%; object-fit: cover;">
                    @else
                        <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="text-white opacity-50 fas fa-newspaper fa-4x"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-2 text-muted small">
                            <i class="far fa-calendar-alt text-primary me-1"></i> 
                            {{ $news->published_at ? $news->published_at->format('d/m/Y') : 'Date non définie' }}
                        </div>
                        <h5 class="card-title fw-bold">{{ $news->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit(strip_tags($news->content), 100) }}</p>
                    </div>
                    <div class="pt-0 bg-transparent border-0 card-footer">
                        <a href="{{ route('public.news.show', $news->slug) }}" class="btn btn-primary w-100">
                            <i class="fas fa-arrow-right me-2"></i> Lire la suite
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-5 text-center">
            <i class="mb-3 fas fa-newspaper fa-4x text-muted"></i>
            <h4>Aucune actualité disponible</h4>
            <p class="text-muted">Revenez bientôt pour découvrir nos actualités.</p>
        </div>
        @endif
    </div>
</section>



<!-- Témoignages -->
<section class="py-5 testimonials-section bg-light">
    <div class="container">
        <h2 class="mb-2 text-center fw-bold" data-aos="fade-up">Ce qu'ils disent de nous</h2>
        <p class="mb-5 text-center text-muted" data-aos="fade-up">Les témoignages de ceux qui nous connaissent</p>
        <div class="row">
            <div class="mb-4 col-md-4" data-aos="fade-up">
                <div class="shadow-sm card h-100">
                    <div class="p-4 text-center card-body">
                        <i class="mb-3 fas fa-quote-left fa-2x text-primary"></i>
                        <p class="card-text font-italic">"Une fanfare exceptionnelle qui apporte joie et spiritualité à travers leur musique. Chaque prestation est un moment de grâce."</p>
                        <div class="mt-3">
                            <div class="mb-1 text-warning">
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
            <div class="mb-4 col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="shadow-sm card h-100">
                    <div class="p-4 text-center card-body">
                        <i class="mb-3 fas fa-quote-left fa-2x text-primary"></i>
                        <p class="card-text font-italic">"Professionnalisme et excellence dans chaque prestation. Une fierté nationale qui rayonne au-delà de nos frontières."</p>
                        <div class="mt-3">
                            <div class="mb-1 text-warning">
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
            <div class="mb-4 col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="shadow-sm card h-100">
                    <div class="p-4 text-center card-body">
                        <i class="mb-3 fas fa-quote-left fa-2x text-primary"></i>
                        <p class="card-text font-italic">"Formidable organisation, discipline et talent. Une référence dans le domaine musical au Bénin et en Afrique."</p>
                        <div class="mt-3">
                            <div class="mb-1 text-warning">
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
<section class="py-5 gallery-section">
    <div class="container">
        <div class="mb-5 d-flex justify-content-between align-items-center" data-aos="fade-up">
            <div>
                <h2 class="mb-0 fw-bold">Galerie</h2>
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
                    <div class="overflow-hidden rounded shadow-sm gallery-item position-relative">
                        @if($gallery->cover_image)
                            <img src="{{ asset('storage/' . $gallery->cover_image) }}" 
                                 alt="{{ $gallery->title }}" 
                                 style="height: 200px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
                        @else
                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="text-white opacity-50 fas fa-images fa-3x"></i>
                            </div>
                        @endif
                        <div class="bottom-0 p-2 text-white bg-opacity-75 overlay position-absolute start-0 end-0 bg-dark">
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
        @else
        <div class="py-5 text-center">
            <i class="mb-3 fas fa-images fa-4x text-muted"></i>
            <h4>Aucune image dans la galerie</h4>
            <p class="text-muted">Revenez bientôt pour découvrir nos photos.</p>
        </div>
        @endif
    </div>
</section>

<!-- Sections -->
<section class="py-5 sections-section bg-light">
    <div class="container">
        <h2 class="mb-2 text-center fw-bold" data-aos="fade-up">Nos sections</h2>
        <p class="mb-5 text-center text-muted" data-aos="fade-up">Découvrez les différentes familles d'instruments de notre fanfare</p>
        <div class="row g-4">
            <div class="col-md-3 col-6" data-aos="fade-up">
                <div class="p-3 text-center bg-white rounded shadow-sm h-100">
                    <i class="mb-2 fas fa-trumpet fa-3x text-primary"></i>
                    <h6 class="fw-bold">Trompettes</h6>
                    <small class="text-muted">25 musiciens</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="50">
                <div class="p-3 text-center bg-white rounded shadow-sm h-100">
                    <i class="mb-2 fas fa-music fa-3x text-success"></i>
                    <h6 class="fw-bold">Trombones</h6>
                    <small class="text-muted">15 musiciens</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-3 text-center bg-white rounded shadow-sm h-100">
                    <i class="mb-2 fas fa-saxophone fa-3x text-warning"></i>
                    <h6 class="fw-bold">Saxophones</h6>
                    <small class="text-muted">18 musiciens</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="150">
                <div class="p-3 text-center bg-white rounded shadow-sm h-100">
                    <i class="mb-2 fas fa-drum fa-3x text-danger"></i>
                    <h6 class="fw-bold">Percussions</h6>
                    <small class="text-muted">20 musiciens</small>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('about') }}" class="btn btn-primary">
                <i class="fas fa-info-circle me-2"></i> En savoir plus sur nos sections
            </a>
        </div>
    </div>
</section>

<!-- Partenaires - Slide Infini -->
<section class="py-5 partners-section" style="background: #f8f9fa; overflow: hidden;">
    <div class="container">
        <h2 class="mb-2 text-center fw-bold" data-aos="fade-up">Nos Partenaires</h2>
        <p class="mb-5 text-center text-muted" data-aos="fade-up">Ils nous accompagnent dans notre mission</p>
        
        @if(isset($partners) && $partners->count() > 0)
        <div class="partner-slider-wrapper" style="position: relative; overflow: hidden;">
            <div class="partner-slider" style="display: flex; animation: slidePartners 20s linear infinite; width: max-content;">
                @foreach($partners as $partner)
                <div class="text-center partner-item" style="min-width: 150px; padding: 0 15px;">
                    <div class="p-3 transition bg-white rounded shadow-sm hover-shadow" style="height: 100px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        @if($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" 
                                 alt="{{ $partner->name }}" 
                                 class="img-fluid" 
                                 style="max-height: 50px; object-fit: contain; filter: grayscale(0); transition: filter 0.3s ease;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 50px;">
                                <i class="fas fa-building fa-2x text-muted"></i>
                            </div>
                        @endif
                        <small class="mt-1 d-block text-muted" style="font-size: 0.7rem;">{{ Str::limit($partner->name, 20) }}</small>
                    </div>
                </div>
                @endforeach
                @foreach($partners as $partner)
                <div class="text-center partner-item" style="min-width: 150px; padding: 0 15px;">
                    <div class="p-3 transition bg-white rounded shadow-sm hover-shadow" style="height: 100px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        @if($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" 
                                 alt="{{ $partner->name }}" 
                                 class="img-fluid" 
                                 style="max-height: 50px; object-fit: contain; filter: grayscale(0); transition: filter 0.3s ease;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 50px;">
                                <i class="fas fa-building fa-2x text-muted"></i>
                            </div>
                        @endif
                        <small class="mt-1 d-block text-muted" style="font-size: 0.7rem;">{{ Str::limit($partner->name, 20) }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="position: absolute; top: 0; left: 0; width: 80px; height: 100%; background: linear-gradient(to right, #f8f9fa, transparent); pointer-events: none; z-index: 2;"></div>
            <div style="position: absolute; top: 0; right: 0; width: 80px; height: 100%; background: linear-gradient(to left, #f8f9fa, transparent); pointer-events: none; z-index: 2;"></div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('about') }}#partenaires" class="btn btn-primary">
                <i class="fas fa-handshake me-2"></i> Voir tous nos partenaires
            </a>
        </div>
        @else
        <div class="py-4 text-center">
            <p class="text-muted">Nos partenaires seront bientôt disponibles.</p>
        </div>
        @endif
    </div>
</section>

<!-- Appel à l'action -->
<section class="py-5 my-10 cta-section" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h2 class="mb-3 fw-bold">Rejoignez la Fanfare Céleste</h2>
        <p class="mb-4 lead">Vous êtes musicien ou vous souhaitez apprendre ? Rejoignez notre grande famille !</p>
        <div class="flex-wrap gap-3 d-flex justify-content-center">
            <a href="{{ route('membership.apply') }}" class="px-5 btn btn-light btn-lg">
                <i class="fas fa-hand-peace me-2"></i> Adhérer maintenant
            </a>
            <a href="{{ route('contact') }}" class="px-5 btn btn-outline-light btn-lg">
                <i class="fas fa-envelope me-2"></i> Nous contacter
            </a>
        </div>
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

    /* Carousel styles */
    .carousel-item {
        transition: transform 0.6s ease;
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.3);
        border-radius: 50%;
        padding: 20px;
        width: 40px;
        height: 40px;
        background-size: 50%;
    }
    
    .carousel-indicators button {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin: 0 5px;
        border: 2px solid rgba(255,255,255,0.5);
    }
    
    .carousel-indicators .active {
        background-color: #fff;
        border-color: #fff;
    }

    .carousel-slide {
        height: 500px;
    }

    /* Ordre des colonnes sur mobile */
    @media (max-width: 991.98px) {
        .order-first {
            order: -1 !important;
        }
        .order-last {
            order: 1 !important;
        }
        .carousel-slide {
            height: 500px !important;
        }
        .display-4 {
            font-size: 2.2rem !important;
        }
    }

    @media (max-width: 768px) {
        .carousel-slide {
            height: 450px !important;
        }
        .display-3 {
            font-size: 2rem !important;
        }
        .lead {
            font-size: 0.95rem !important;
        }
        .btn-lg {
            font-size: 0.85rem !important;
            padding: 0.5rem 1rem !important;
        }
        .hero-section {
            padding: 40px 0 !important;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 30px !important;
            height: 30px !important;
            padding: 10px !important;
        }
    }

    @media (max-width: 576px) {
        .carousel-slide {
            height: 420px !important;
        }
        .display-3 {
            font-size: 1.6rem !important;
        }
        .lead {
            font-size: 0.85rem !important;
        }
        .btn-lg {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.8rem !important;
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
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

    // Initialisation du carousel
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.querySelector('#heroCarousel');
        if (myCarousel) {
            var carousel = new bootstrap.Carousel(myCarousel, {
                interval: 5000,
                ride: 'carousel',
                pause: 'hover',
                wrap: true
            });
        }
    });
</script>
@endpush
@endsection
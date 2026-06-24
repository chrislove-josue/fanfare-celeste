@extends('layouts.app')

@section('title', 'À propos - Fanfare Céleste')

@section('content')
<!-- En-tête -->
<section class="about-hero py-5" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">À propos de nous</h1>
        <p class="lead mb-0">Découvrez l'histoire, la mission et les valeurs de la Fanfare Céleste</p>
    </div>
</section>

<div class="container py-5">
    <!-- Navigation interne -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="#histoire" class="btn btn-outline-primary btn-sm">Histoire</a>
                <a href="#mission-vision" class="btn btn-outline-primary btn-sm">Mission & Vision</a>
                <a href="#bureau" class="btn btn-outline-primary btn-sm">Bureau exécutif</a>
                <a href="#sections" class="btn btn-outline-primary btn-sm">Sections</a>
                <a href="#partenaires" class="btn btn-outline-primary btn-sm">Partenaires</a>
            </div>
        </div>
    </div>

    <!-- Histoire -->
    <div id="histoire" class="row mb-5 align-items-center">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="fw-bold mb-3">Notre Histoire</h2>
            @if($historyPage)
                <div class="content">
                    {!! $historyPage->content !!}
                </div>
            @else
                <div class="content">
                    <p class="text-muted">Fondée en 2010, la Fanfare Céleste est née d'une vision : utiliser la musique comme un instrument de louange et de rassemblement. Depuis, elle n'a cessé de grandir et de rayonner, portant haut les couleurs de la foi et de l'excellence musicale.</p>
                    <p class="text-muted">Notre fanfare a su conquérir le cœur de nombreux fidèles et mélomanes à travers ses prestations lors des grandes cérémonies religieuses, des concerts et des festivals. Chaque note jouée est une prière, chaque mélodie est un témoignage.</p>
                </div>
            @endif
        </div>
        <div class="col-lg-6 text-center" data-aos="fade-left">
            <img src="https://images.unsplash.com/photo-1507838153414-b4b713384a76?w=600&h=400&fit=crop" 
                 alt="Histoire" class="img-fluid rounded shadow" 
                 style="height: 350px; width: 100%; object-fit: cover;">
        </div>
    </div>

    <hr>

    <!-- Mission & Vision -->
    <div id="mission-vision" class="row mb-5 g-4">
        <div class="col-md-6" data-aos="fade-up">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-bullseye fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Notre Mission</h3>
                    @if($missionPage)
                        <div class="text-muted">
                            {!! $missionPage->content !!}
                        </div>
                    @else
                        <p class="text-muted">Annoncer la bonne nouvelle à travers la musique, en formant des musiciens talentueux et en offrant des prestations de qualité qui élèvent les cœurs vers Dieu.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body text-center p-4">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-eye fa-3x text-success"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Notre Vision</h3>
                    @if($visionPage)
                        <div class="text-muted">
                            {!! $visionPage->content !!}
                        </div>
                    @else
                        <p class="text-muted">Devenir une référence musicale en Afrique de l'Ouest, en alliant excellence artistique et rayonnement spirituel, pour toucher les cœurs et transformer les vies.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Bureau exécutif -->
    <div id="bureau" class="mb-5">
        <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Bureau Exécutif</h2>
        <p class="text-center text-muted mb-5" data-aos="fade-up">L'équipe dirigeante de la Fanfare Céleste</p>
        
        @if($committeeMembers->count() > 0)
        <div class="row g-4 justify-content-center">
            @foreach($committeeMembers as $member)
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="card h-100 shadow-sm text-center hover-card">
                    <div class="card-body">
                        @if($member->member && $member->member->photo)
                            <img src="{{ asset('storage/' . $member->member->photo) }}" 
                                 alt="{{ $member->member->full_name }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #3946B2;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 100px; height: 100px; font-size: 32px; font-weight: 700;">
                                {{ $member->member ? strtoupper(substr($member->member->first_name, 0, 1) . substr($member->member->last_name, 0, 1)) : '??' }}
                            </div>
                        @endif
                        <h6 class="fw-bold mb-1">{{ $member->member->full_name ?? 'N/A' }}</h6>
                        <p class="text-primary small mb-2">{{ $positions[$member->position] ?? $member->position }}</p>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-envelope me-1"></i> {{ $member->member->email ?? 'Non renseigné' }}
                        </p>
                        @if($member->member && $member->member->phone)
                            <p class="text-muted small mb-0">
                                <i class="fas fa-phone me-1"></i> {{ $member->member->phone }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-4">
            <p class="text-muted">Le bureau exécutif sera bientôt présenté.</p>
        </div>
        @endif
    </div>

    <hr>

    <!-- Sections -->
    <div id="sections" class="mb-5">
        <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Nos Sections</h2>
        <p class="text-center text-muted mb-5" data-aos="fade-up">Découvrez les différentes familles d'instruments de notre fanfare</p>
        
        @if(isset($sections) && count($sections) > 0)
        <div class="row g-4">
            @foreach($sections as $key => $section)
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="section-card text-center p-4 bg-white rounded shadow-sm h-100 hover-card">
                    <div class="icon-wrapper mb-3">
                        <i class="fas {{ $section['icon'] }} fa-3x text-primary"></i>
                    </div>
                    <h6 class="fw-bold">{{ $section['name'] }}</h6>
                    <p class="text-muted small mb-2">{{ $section['members_count'] ?? 0 }} musiciens</p>
                    @if(isset($section['leader']))
                        <small class="text-muted d-block">Chef : {{ $section['leader'] }}</small>
                    @endif
                    @if(isset($section['description']))
                        <p class="text-muted small mt-2">{{ Str::limit($section['description'], 60) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-4">
            <p class="text-muted">Les sections seront bientôt présentées.</p>
        </div>
        @endif
    </div>

    <hr>

    <!-- Partenaires -->
    <div id="partenaires" class="mb-5">
        <h2 class="text-center fw-bold mb-4" data-aos="fade-up">Nos Partenaires</h2>
        <p class="text-center text-muted mb-5" data-aos="fade-up">Ils nous accompagnent dans notre mission</p>
        
        @if(isset($partners) && $partners->count() > 0)
            @foreach($partnerCategories as $categoryKey => $categoryLabel)
                @php
                    $categoryPartners = $partners->where('category', $categoryKey);
                @endphp
                @if($categoryPartners->count() > 0)
                <div class="mb-5">
                    <h5 class="text-center fw-bold mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2">{{ $categoryLabel }}</span>
                    </h5>
                    <div class="row g-4 justify-content-center">
                        @foreach($categoryPartners as $partner)
                        <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div class="card h-100 shadow-sm text-center border-0 hover-card">
                                <div class="card-body p-3">
                                    @if($partner->logo_url)
                                        <img src="{{ $partner->logo_url }}" 
                                             alt="{{ $partner->name }}" 
                                             class="img-fluid mb-2" 
                                             style="height: 80px; object-fit: contain;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-2" 
                                             style="height: 80px; width: 80px;">
                                            <i class="fas fa-building fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                    <h6 class="fw-bold mb-1">{{ $partner->name }}</h6>
                                    @if($partner->description)
                                        <small class="text-muted d-block">{{ Str::limit($partner->description, 60) }}</small>
                                    @endif
                                    @if($partner->website)
                                        <a href="{{ $partner->website }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-external-link-alt me-1"></i> Visiter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        @else
        <div class="text-center py-4">
            <p class="text-muted">Nos partenaires seront bientôt disponibles.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
        position: relative;
        overflow: hidden;
    }
    .about-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 500px;
        height: 500px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    
    #histoire .content {
        font-size: 1.05rem;
        line-height: 1.8;
    }
    
    #histoire .content p {
        margin-bottom: 1rem;
    }

    .hover-card {
        transition: all 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: rgba(57, 70, 178, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .section-card {
        transition: all 0.3s ease;
    }
    
    .section-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1) !important;
    }

    .section-card .icon-wrapper {
        width: 60px;
        height: 60px;
    }

    /* Animation de la navigation interne */
    .btn-outline-primary {
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(57, 70, 178, 0.2);
    }
</style>
@endpush
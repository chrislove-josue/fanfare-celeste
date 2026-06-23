@extends('layouts.app')

@section('title', 'Galerie - Fanfare Céleste')

@section('content')
<section class="gallery-hero py-5" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Notre Galerie</h1>
        <p class="lead">Découvrez nos moments forts en images et vidéos</p>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <span class="badge bg-primary px-4 py-2">
                <i class="fas fa-images me-2"></i> {{ $totalGalleries }} photos
            </span>
            <span class="badge bg-success px-4 py-2">
                <i class="fas fa-video me-2"></i> {{ $totalVideos }} vidéos
            </span>
            <span class="badge bg-warning text-dark px-4 py-2">
                <i class="fas fa-trophy me-2"></i> Distinctions
            </span>
        </div>
    </div>
</section>

<!-- Filtres -->
<section class="filters-section py-4 bg-light border-bottom">
    <div class="container">
        <form method="GET" action="{{ route('public.gallery.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les types</option>
                    <option value="photo" {{ request('type') == 'photo' ? 'selected' : '' }}>Photos</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéos</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->anyFilled(['category', 'type', 'search']))
                        <a href="{{ route('public.gallery.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Catégories en vedette -->
<section class="featured-categories py-4">
    <div class="container">
        <div class="row g-3">
            @foreach($categories as $key => $label)
                @if(isset($latestByCategory[$key]) && $latestByCategory[$key]->count() > 0)
                <div class="col-md-4 col-lg-2">
                    <a href="{{ route('public.gallery.category', $key) }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow transition">
                            <div class="card-body">
                                @if($key === 'trophies')
                                    <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                @elseif($key === 'concerts')
                                    <i class="fas fa-music fa-2x text-primary mb-2"></i>
                                @elseif($key === 'events')
                                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                @elseif($key === 'members')
                                    <i class="fas fa-users fa-2x text-info mb-2"></i>
                                @elseif($key === 'ceremonies')
                                    <i class="fas fa-church fa-2x text-danger mb-2"></i>
                                @else
                                    <i class="fas fa-image fa-2x text-secondary mb-2"></i>
                                @endif
                                <h6 class="fw-bold mb-0">{{ $label }}</h6>
                                <small class="text-muted">{{ $latestByCategory[$key]->count() }} photos</small>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Galerie -->
<section class="gallery-grid py-4">
    <div class="container">
        @if($galleries->count() > 0)
        <div class="row g-3">
            @foreach($galleries as $gallery)
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="gallery-card position-relative overflow-hidden rounded shadow-sm hover-shadow transition">
                    <a href="{{ route('public.gallery.show', $gallery) }}" class="text-decoration-none">
                        <div class="position-relative">
                            @if($gallery->type == 'photo')
                                <img src="{{ $gallery->file_url }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="img-fluid w-100" 
                                     style="height: 250px; object-fit: cover; transition: transform 0.3s ease;">
                            @else
                                <video src="{{ $gallery->file_url }}" 
                                       class="img-fluid w-100" 
                                       style="height: 250px; object-fit: cover;"
                                       muted></video>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play-circle fa-3x text-white opacity-75"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">
                                    <i class="fas fa-{{ $gallery->type_icon }} me-1"></i>
                                    {{ $gallery->type }}
                                </span>
                            </div>
                            @if($gallery->category === 'trophies')
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-trophy me-1"></i> Distinction
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 bg-white">
                            <h6 class="fw-bold mb-1 text-dark">{{ Str::limit($gallery->title, 40) }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i> 
                                    {{ $gallery->category_label }}
                                </small>
                                <div class="d-flex gap-2">
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i> {{ $gallery->views_count ?? 0 }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-heart me-1 text-danger"></i> {{ $gallery->likes_count ?? 0 }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $galleries->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <h4>Aucun contenu trouvé</h4>
            <p class="text-muted">La galerie sera bientôt enrichie avec nos photos.</p>
        </div>
        @endif
    </div>
</section>

<!-- Section Distinctions -->
<section class="achievements-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-2">🏆 Nos Distinctions</h2>
        <p class="text-center text-muted mb-4">Les trophées et reconnaissances qui jalonnent notre parcours</p>
        <div class="row g-4">
            @php
                $achievements = [
                    [
                        'title' => 'Trophée d\'Excellence 2024',
                        'description' => 'Premier prix au Concours National des Fanfares',
                        'icon' => 'fa-trophy',
                        'color' => 'warning',
                    ],
                    [
                        'title' => 'Distinction Ministérielle',
                        'description' => 'Reconnaissance du Ministère de la Culture',
                        'icon' => 'fa-medal',
                        'color' => 'primary',
                    ],
                    [
                        'title' => 'Prix du Public',
                        'description' => 'Meilleure fanfare selon le public au Festival International',
                        'icon' => 'fa-crown',
                        'color' => 'success',
                    ],
                    [
                        'title' => 'Certificat d\'Excellence',
                        'description' => 'Reconnue comme référence musicale en Afrique de l\'Ouest',
                        'icon' => 'fa-award',
                        'color' => 'danger',
                    ],
                ];
            @endphp
            @foreach($achievements as $achievement)
            <div class="col-md-3 col-6" data-aos="fade-up">
                <div class="card h-100 text-center border-0 shadow-sm hover-shadow transition">
                    <div class="card-body">
                        <div class="bg-{{ $achievement['color'] }} bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas {{ $achievement['icon'] }} fa-2x text-{{ $achievement['color'] }}"></i>
                        </div>
                        <h6 class="fw-bold">{{ $achievement['title'] }}</h6>
                        <p class="text-muted small mb-0">{{ $achievement['description'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
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
    .gallery-card:hover img {
        transform: scale(1.05);
    }
    .gallery-card:hover video {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
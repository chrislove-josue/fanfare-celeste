{{-- resources/views/public/gallery/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Galerie - Fanfare Céleste')

@section('content')
<!-- En-tête de la galerie -->
<section class="gallery-hero py-5" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Notre Galerie</h1>
        <p class="lead">Découvrez nos albums photos et vidéos</p>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <span class="badge bg-primary px-4 py-2">
                <i class="fas fa-images me-2"></i> {{ $totalGalleries ?? 0 }} albums
            </span>
            <span>
                <a href="https://www.facebook.com/profile.php?id=61553222979542" 
               target="_blank" 
               class="btn btn-primary btn-sm"
               title="Partager sur Facebook">
                <i class="fab fa-facebook-f me-1"></i> Facebook
            </a>
            </span>
            <span class="badge bg-success px-4 py-2">
                <i class="fas fa-image me-2"></i> {{ $totalPhotos ?? 0 }} photos
            </span>
            <span class="badge bg-danger px-4 py-2">
                <i class="fas fa-video me-2"></i> {{ isset($videos) ? count($videos) : 0 }} vidéos
            </span>
            
        </div>
    </div>
</section>

<!-- Boutons de navigation entre Galerie et Vidéos -->
<section class="navigation-switch py-4 bg-light border-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="btn-group w-100" role="group" aria-label="Navigation Galerie">
                    <button type="button" class="btn btn-primary btn-lg active" id="btn-gallery" onclick="showGallery()">
                        <i class="fas fa-images me-2"></i> Galerie Photos
                        <span class="badge bg-light text-dark ms-2">{{ $totalGalleries ?? 0 }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-lg" id="btn-videos" onclick="showVideos()">
                        <i class="fab fa-youtube me-2"></i>Galerie Vidéos
                        <span class="badge bg-danger text-white ms-2">{{ isset($videos) ? count($videos) : 0 }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- SECTION GALERIE (visible par défaut)       -->
<!-- ========================================== -->
<section id="gallery-section" class="gallery-section">
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
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request()->anyFilled(['category', 'search']))
                            <a href="{{ route('public.gallery.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <span class="text-muted small">{{ $galleries->total() }} albums</span>
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
                                    <small class="text-muted">{{ $latestByCategory[$key]->count() }} albums</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Galerie d'images -->
    <section class="gallery-grid py-4">
        <div class="container">
            @if($galleries->count() > 0)
            <div class="row g-4">
                @foreach($galleries as $gallery)
                <div class="col-md-4 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="card h-100 shadow-sm hover-shadow transition">
                        <a href="{{ route('public.gallery.show', $gallery) }}" class="text-decoration-none">
                            <div class="position-relative">
                                @if($gallery->cover_image_url)
                                    <img src="{{ $gallery->cover_image_url }}" 
                                         alt="{{ $gallery->title }}" 
                                         class="card-img-top" 
                                         style="height: 220px; object-fit: cover;">
                                @else
                                    <div style="height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-images fa-4x text-white opacity-50"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75 px-3 py-2">
                                        <i class="fas fa-{{ $gallery->category === 'trophies' ? 'trophy' : 'image' }} me-1"></i>
                                        {{ $gallery->category_label }}
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 start-0 m-2">
                                    <span class="badge bg-primary px-3 py-2">
                                        <i class="fas fa-photo-video me-1"></i> {{ $gallery->media_count }} médias
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark">{{ Str::limit($gallery->title, 50) }}</h5>
                                @if($gallery->description)
                                    <p class="card-text text-muted small">{{ Str::limit($gallery->description, 80) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> 
                                        {{ $gallery->created_at->format('d/m/Y') }}
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
                {{ $galleries->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                <h4>Aucune galerie trouvée</h4>
                <p class="text-muted">Revenez bientôt pour découvrir nos albums.</p>
            </div>
            @endif
        </div>
    </section>
</section>

<!-- ========================================== -->
<!-- SECTION VIDÉOS (cachée par défaut)         -->
<!-- ========================================== -->
<section id="videos-section" class="videos-section" style="display: none;">
    <div class="container py-4">

        <!-- Grille des vidéos -->
        @if(isset($videos) && count($videos) > 0)
        <div class="row g-4">
            @foreach($videos as $video)
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
                <div class="video-card bg-white rounded-3 shadow-sm hover-shadow transition overflow-hidden">
                    <a href="{{ route('videos.show', $video['id']) }}" class="text-decoration-none">
                        <div class="position-relative">
                            <img src="{{ $video['thumbnail'] }}" 
                                 alt="{{ $video['title'] }}" 
                                 class="w-100" 
                                 style="height: 180px; object-fit: cover;">
                            <div class="position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-50 rounded-circle p-3">
                                <i class="fas fa-play fa-2x text-white"></i>
                            </div>
                        </div>
                        <div class="p-3">
                            <h6 class="card-title fw-bold text-dark" style="font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $video['title'] }}
                            </h6>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fab fa-youtube fa-4x text-danger mb-3"></i>
            <h4>Aucune vidéo disponible</h4>
            <p class="text-muted">Les vidéos seront bientôt ajoutées.</p>
        </div>
        @endif

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
    
    /* Style des boutons de navigation */
    .btn-group .btn {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 0;
        transition: all 0.3s ease;
    }
    .btn-group .btn:first-child {
        border-radius: 50px 0 0 50px;
    }
    .btn-group .btn:last-child {
        border-radius: 0 50px 50px 0;
    }
    .btn-group .btn.active {
        box-shadow: 0 5px 20px rgba(26, 35, 126, 0.3);
    }
    .btn-group .btn:not(.active):hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Vidéos */
    .video-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .video-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
    }
    .video-card:hover .fa-play {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
    .fa-play {
        transition: transform 0.3s ease;
    }
    .channel-header {
        border-left: 5px solid #ff0000;
    }
    
    /* Animations */
    #gallery-section,
    #videos-section {
        animation: fadeSlide 0.4s ease;
    }
    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(20px);
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
    // Fonction pour afficher la galerie
    function showGallery() {
        document.getElementById('gallery-section').style.display = 'block';
        document.getElementById('videos-section').style.display = 'none';
        
        document.getElementById('btn-gallery').className = 'btn btn-primary btn-lg active';
        document.getElementById('btn-videos').className = 'btn btn-outline-primary btn-lg';
        
        // Sauvegarder le choix
        localStorage.setItem('galleryView', 'gallery');
        
        // Recharger AOS pour les animations
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }
    
    // Fonction pour afficher les vidéos
    function showVideos() {
        document.getElementById('gallery-section').style.display = 'none';
        document.getElementById('videos-section').style.display = 'block';
        
        document.getElementById('btn-gallery').className = 'btn btn-outline-primary btn-lg';
        document.getElementById('btn-videos').className = 'btn btn-primary btn-lg active';
        
        // Sauvegarder le choix
        localStorage.setItem('galleryView', 'videos');
        
        // Recharger AOS pour les animations
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }
    
    // Restaurer la vue sauvegardée au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('galleryView');
        if (savedView === 'videos') {
            showVideos();
        } else {
            showGallery(); // Par défaut
        }
    });
</script>
@endpush
@endsection
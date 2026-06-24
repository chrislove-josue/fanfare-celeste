@extends('layouts.app')

@section('title', 'Galerie - Fanfare Céleste')

@section('content')
<section class="gallery-hero py-5" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Notre Galerie</h1>
        <p class="lead">Découvrez nos albums photos et vidéos</p>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <span class="badge bg-primary px-4 py-2">
                <i class="fas fa-images me-2"></i> {{ $totalGalleries ?? 0 }} albums
            </span>
            <span class="badge bg-success px-4 py-2">
                <i class="fas fa-image me-2"></i> {{ $totalPhotos ?? 0 }} photos
            </span>
            <span class="badge bg-danger px-4 py-2">
                <i class="fas fa-video me-2"></i> {{ $totalVideos ?? 0 }} vidéos
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

<!-- Galerie -->
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
</style>
@endpush
@endsection
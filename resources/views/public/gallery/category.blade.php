@extends('layouts.app')

@section('title', $categoryLabel . ' - Galerie Fanfare Céleste')

@section('content')
<!-- En-tête -->
<section class="gallery-hero py-5" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">{{ $categoryLabel }}</h1>
        <p class="lead mb-4">Découvrez tous nos albums dans cette catégorie</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('public.gallery.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i> Retour à la galerie
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-home me-2"></i> Accueil
            </a>
        </div>
    </div>
</section>

<!-- Galerie -->
<section class="gallery-grid py-5">
    <div class="container">
        @if($galleries->count() > 0)
        <div class="row g-4">
            @foreach($galleries as $gallery)
            <div class="col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="card h-100 shadow-sm hover-shadow transition">
                    <a href="{{ route('public.gallery.show', $gallery) }}" class="text-decoration-none">
                        <div class="position-relative">
                            @if($gallery->cover_image_url)
                                <img src="{{ $gallery->cover_image_url }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-images fa-4x text-white opacity-50"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="fas fa-photo-video me-1"></i> {{ $gallery->media_count }} médias
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold mb-1 text-dark">{{ Str::limit($gallery->title, 40) }}</h6>
                            @if($gallery->description)
                                <p class="text-muted small mb-2">{{ Str::limit($gallery->description, 60) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
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

        <!-- Pagination -->
        <div class="mt-4">
            {{ $galleries->appends(request()->query())->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">Aucun album dans cette catégorie</h4>
            <p class="text-muted">Revenez bientôt pour découvrir nos nouvelles photos.</p>
            <div class="mt-4">
                <a href="{{ route('public.gallery.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à la galerie
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Autres catégories -->
<section class="other-categories py-5 bg-light">
    <div class="container">
        <h3 class="text-center fw-bold mb-4">Explorez d'autres catégories</h3>
        <div class="row g-3 justify-content-center">
            @php
                $allCategories = \App\Models\Gallery::getCategories();
            @endphp
            @foreach($allCategories as $key => $label)
                @if($key !== $category)
                <div class="col-md-2 col-4">
                    <a href="{{ route('public.gallery.category', $key) }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow transition border-0">
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
                                <h6 class="fw-bold mb-0" style="font-size: 0.8rem;">{{ $label }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endif
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
</style>
@endpush
@endsection
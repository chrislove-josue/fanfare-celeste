@extends('layouts.app')

@section('title', $categoryLabel . ' - Galerie Fanfare Céleste')

@section('content')
<!-- En-tête -->
<section class="gallery-hero py-5" style="background: linear-gradient(135deg, #1a237e, #283593); color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">{{ $categoryLabel }}</h1>
        <p class="lead mb-4">Découvrez tous nos contenus dans cette catégorie</p>
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
                <div class="gallery-card position-relative overflow-hidden rounded shadow-sm hover-shadow transition">
                    <a href="{{ route('public.gallery.show', $gallery) }}" class="text-decoration-none">
                        <div class="position-relative">
                            @if($gallery->file_path)
                                <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="img-fluid w-100" 
                                     style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">
                            @else
                                <div style="height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image fa-3x text-white opacity-50"></i>
                                </div>
                            @endif
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75 px-3 py-2">
                                    <i class="fas fa-{{ $gallery->type === 'photo' ? 'image' : 'video' }} me-1"></i>
                                    {{ ucfirst($gallery->type) }}
                                </span>
                            </div>
                            
                            @if($gallery->category === 'trophies')
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-trophy me-1"></i> Distinction
                                    </span>
                                </div>
                            @endif
                            
                            @if($gallery->is_new)
                                <div class="position-absolute bottom-0 start-0 m-2">
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-star me-1"></i> Nouveau
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-3 bg-white">
                            <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">
                                {{ Str::limit($gallery->title, 40) }}
                            </h6>
                            
                            @if($gallery->description)
                                <p class="text-muted small mb-2">
                                    {{ Str::limit($gallery->description, 60) }}
                                </p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i> 
                                    {{ $gallery->created_at->format('d/m/Y') }}
                                </small>
                                <div class="d-flex gap-3">
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
        <div class="mt-5">
            {{ $galleries->appends(request()->query())->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">Aucun contenu dans cette catégorie</h4>
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
                $categories = \App\Models\Gallery::getCategories();
            @endphp
            @foreach($categories as $key => $label)
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
    .gallery-card:hover img {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
@extends('layouts.app')

@section('title', $gallery->title . ' - Galerie Fanfare Céleste')

@section('content')
<section class="gallery-detail py-5">
    <div class="container">
        <!-- Fil d'Ariane -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.gallery.index') }}">Galerie</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('public.gallery.category', $gallery->category) }}">
                        {{ $gallery->category_label }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ Str::limit($gallery->title, 30) }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Image principale -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 overflow-hidden">
                    <div class="card-body p-0">
                        @if($gallery->type == 'photo')
                            <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                                 alt="{{ $gallery->title }}" 
                                 class="img-fluid w-100" 
                                 style="max-height: 600px; object-fit: contain; background: #f8f9fa;">
                        @else
                            <video src="{{ asset('storage/' . $gallery->file_path) }}" 
                                   class="img-fluid w-100" 
                                   style="max-height: 600px; object-fit: contain; background: #f8f9fa;"
                                   controls></video>
                        @endif
                    </div>
                </div>

                <!-- Navigation entre les images -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('public.gallery.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la galerie
                    </a>
                    <div>
                        <span class="badge bg-primary me-1">
                            <i class="fas fa-eye me-1"></i> {{ $gallery->views_count ?? 0 }} vues
                        </span>
                        <span class="badge bg-danger">
                            <i class="fas fa-heart me-1"></i> {{ $gallery->likes_count ?? 0 }} likes
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informations -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="fw-bold mb-3">{{ $gallery->title }}</h3>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary me-1">
                                <i class="fas fa-tag me-1"></i> {{ $gallery->category_label }}
                            </span>
                            <span class="badge bg-secondary me-1">
                                <i class="fas fa-{{ $gallery->type_icon }} me-1"></i> {{ ucfirst($gallery->type) }}
                            </span>
                            @if($gallery->category === 'trophies')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-trophy me-1"></i> Distinction
                                </span>
                            @endif
                            @if($gallery->is_new)
                                <span class="badge bg-danger">
                                    <i class="fas fa-star me-1"></i> Nouveau
                                </span>
                            @endif
                        </div>

                        @if($gallery->description)
                            <p class="text-muted">{{ $gallery->description }}</p>
                        @endif

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Date d'ajout</span>
                                <span>{{ $gallery->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Catégorie</span>
                                <span>{{ $gallery->category_label }}</span>
                            </div>
                            @if($gallery->file_size)
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Taille du fichier</span>
                                <span>{{ $gallery->formatted_size }}</span>
                            </div>
                            @endif
                        </div>

                        <hr>

                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <form action="{{ route('gallery.like', $gallery) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-heart me-2"></i> 
                                    J'aime ({{ $gallery->likes_count ?? 0 }})
                                </button>
                            </form>
                            
                            <a href="{{ asset('storage/' . $gallery->file_path) }}" 
                               download="{{ $gallery->file_name }}" 
                               class="btn btn-primary">
                                <i class="fas fa-download me-2"></i> Télécharger
                            </a>
                            
                            <button onclick="window.print()" class="btn btn-secondary">
                                <i class="fas fa-print me-2"></i> Imprimer
                            </button>
                            
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank" 
                               class="btn btn-outline-primary">
                                <i class="fab fa-facebook me-2"></i> Partager
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Images similaires -->
                @if($related->count() > 0)
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-transparent">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-images me-2 text-primary"></i>
                            Photos similaires
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($related as $item)
                            <div class="col-6">
                                <a href="{{ route('public.gallery.show', $item) }}" class="text-decoration-none">
                                    @if($item->file_path)
                                        <img src="{{ asset('storage/' . $item->file_path) }}" 
                                             alt="{{ $item->title }}" 
                                             class="img-fluid rounded" 
                                             style="height: 80px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                    @else
                                        <div style="height: 80px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block text-truncate mt-1">{{ Str::limit($item->title, 20) }}</small>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .gallery-detail .card {
        transition: all 0.3s ease;
    }
    .gallery-detail .card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection
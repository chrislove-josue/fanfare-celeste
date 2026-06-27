@extends('layouts.app')

@section('title', $gallery->title . ' - Galerie Fanfare Céleste')

@section('content')
<section class="gallery-detail py-5" style="background: #f8f9fa;">
    <div class="container">
        <!-- Fil d'Ariane -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.gallery.index') }}" class="text-decoration-none">Galerie</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('public.gallery.category', $gallery->category) }}" class="text-decoration-none">
                        {{ $gallery->category_label }}
                    </a>
                </li>
                <li class="breadcrumb-item active">{{ Str::limit($gallery->title, 30) }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Colonne de gauche : Photo principale + Description -->
            <div class="col-lg-8">
                <!-- Titre -->
                <h1 class="fw-bold mb-3">{{ $gallery->title }}</h1>

                <!-- Photo principale -->
                <div class="main-image mb-3">
                    @php
                        $mainMedia = $media->where('is_cover', true)->first() ?? $media->first();
                    @endphp
                    @if($mainMedia)
                        <div class="position-relative rounded shadow-sm overflow-hidden" style="background: #000;">
                            @if($mainMedia->type == 'photo')
                                <img src="{{ $mainMedia->file_url }}" 
                                     alt="{{ $mainMedia->title ?? $gallery->title }}" 
                                     class="img-fluid w-100" 
                                     style="max-height: 500px; object-fit: contain; cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#mediaModal{{ $mainMedia->id }}">
                            @else
                                <video src="{{ $mainMedia->file_url }}" 
                                       class="img-fluid w-100" 
                                       style="max-height: 500px; object-fit: contain;"
                                       controls></video>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 px-3 py-2">
                                    <i class="fas fa-{{ $mainMedia->type_icon }} me-1"></i>
                                    {{ ucfirst($mainMedia->type) }}
                                </span>
                            </div>
                            @if($mainMedia->is_cover)
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-star me-1"></i> Une
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Badges d'information -->
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge bg-primary px-3 py-2">{{ $gallery->category_label }}</span>
                        @if($gallery->event_date)
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-calendar me-1"></i> {{ $gallery->event_date->format('d/m/Y') }}
                            </span>
                        @endif
                        @if($gallery->location)
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $gallery->location }}
                            </span>
                        @endif
                        <span class="badge bg-secondary px-3 py-2">
                            <i class="fas fa-photo-video me-1"></i> {{ $media->count() }} médias
                        </span>
                        <span class="badge bg-dark px-3 py-2">
                            <i class="fas fa-eye me-1"></i> {{ $gallery->views_count ?? 0 }} vues
                        </span>
                        <span class="badge bg-danger px-3 py-2">
                            <i class="fas fa-heart me-1"></i> {{ $gallery->likes_count ?? 0 }} likes
                        </span>
                    </div>
                </div>

                <!-- Description -->
                @if($gallery->description)
                    <div class="description-box bg-white p-4 rounded shadow-sm mb-3">
                        <h5 class="fw-bold mb-2"><i class="fas fa-align-left me-2 text-primary"></i>Description</h5>
                        <p class="text-muted mb-0">{{ $gallery->description }}</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="actions-box bg-white p-3 rounded shadow-sm">
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <form action="{{ route('gallery.like', $gallery) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-heart me-1"></i> J'aime ({{ $gallery->likes_count ?? 0 }})
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite -->
            <div class="col-lg-4">
                <div class="d-flex flex-column" style="position: sticky; top: 20px;">
                    <!-- Autres médias -->
                    <div class="side-gallery mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-images me-2 text-primary"></i>
                            Autres médias
                        </h5>
                        <div class="row g-2">
                            @php
                                $otherMedia = $media->where('is_cover', false)->where('id', '!=', ($mainMedia->id ?? 0));
                                $displayMedia = $otherMedia->take(6);
                            @endphp
                            @if($displayMedia->count() > 0)
                                @foreach($displayMedia as $item)
                                    <div class="col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                        <div class="side-media-item position-relative rounded overflow-hidden shadow-sm hover-shadow transition" 
                                             style="cursor: pointer;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#mediaModal{{ $item->id }}">
                                            @if($item->type == 'photo')
                                                <img src="{{ $item->file_url }}" 
                                                     alt="{{ $item->title ?? $gallery->title }}" 
                                                     class="img-fluid w-100" 
                                                     style="height: 150px; object-fit: cover;">
                                            @else
                                                <video src="{{ $item->file_url }}" 
                                                       class="img-fluid w-100" 
                                                       style="height: 150px; object-fit: cover;"
                                                       muted></video>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="fas fa-play-circle fa-2x text-white opacity-75"></i>
                                                </div>
                                            @endif
                                            <div class="position-absolute top-0 end-0 m-1">
                                                <span class="badge bg-dark bg-opacity-75">
                                                    <i class="fas fa-{{ $item->type_icon }}"></i>
                                                </span>
                                            </div>
                                            @if($item->title)
                                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-1">
                                                    <small class="d-block text-truncate" style="font-size: 0.7rem;">{{ $item->title }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-muted col-12">
                                    <i class="fas fa-photo-video fa-2x mb-2 d-block"></i>
                                    <small>Aucun autre média</small>
                                </div>
                            @endif
                        </div>

                        @if($otherMedia->count() > 6)
                            <div class="text-center mt-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="document.getElementById('more-media').classList.toggle('d-none')">
                                    <i class="fas fa-plus-circle me-1"></i> Voir plus de médias
                                </button>
                            </div>
                            <div id="more-media" class="row g-2 mt-2 d-none">
                                @foreach($otherMedia->skip(6) as $item)
                                    <div class="col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                        <div class="side-media-item position-relative rounded overflow-hidden shadow-sm hover-shadow transition" 
                                             style="cursor: pointer;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#mediaModal{{ $item->id }}">
                                            @if($item->type == 'photo')
                                                <img src="{{ $item->file_url }}" 
                                                     alt="{{ $item->title ?? $gallery->title }}" 
                                                     class="img-fluid w-100" 
                                                     style="height: 150px; object-fit: cover;">
                                            @else
                                                <video src="{{ $item->file_url }}" 
                                                       class="img-fluid w-100" 
                                                       style="height: 150px; object-fit: cover;"
                                                       muted></video>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <i class="fas fa-play-circle fa-2x text-white opacity-75"></i>
                                                </div>
                                            @endif
                                            <div class="position-absolute top-0 end-0 m-1">
                                                <span class="badge bg-dark bg-opacity-75">
                                                    <i class="fas fa-{{ $item->type_icon }}"></i>
                                                </span>
                                            </div>
                                            @if($item->title)
                                                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-1">
                                                    <small class="d-block text-truncate" style="font-size: 0.7rem;">{{ $item->title }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Séparateur -->
                    <hr>

                    <!-- Albums similaires - FIXÉ EN BAS -->
                    @if($related->count() > 0)
                        <div class="related-albums">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-images me-2 text-primary" style="font-size: 1.2rem;"></i>
                                <h5 class="fw-bold mb-0">Albums similaires</h5>
                                <span class="badge bg-primary ms-2">{{ $related->count() }}</span>
                            </div>
                            @foreach($related as $item)
                                <a href="{{ route('public.gallery.show', $item) }}" class="text-decoration-none">
                                    <div class="related-item d-flex align-items-center gap-3 p-3 bg-white rounded shadow-sm mb-2 hover-shadow transition">
                                        @if($item->cover_image_url)
                                            <img src="{{ $item->cover_image_url }}" 
                                                 alt="{{ $item->title }}" 
                                                 style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 70px; height: 70px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-images text-muted fa-2x"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-dark text-truncate">{{ $item->title }}</div>
                                            <div class="d-flex gap-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-photo-video me-1"></i> {{ $item->media_count }} médias
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-eye me-1"></i> {{ $item->views_count ?? 0 }}
                                                </small>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Retour à la galerie -->
                    <div class="mt-4">
                        <a href="{{ route('public.gallery.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i> Retour à la galerie
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modales pour chaque média -->
        @foreach($media as $item)
        <div class="modal fade" id="mediaModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">{{ $item->title ?? $gallery->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        @if($item->type == 'photo')
                            <img src="{{ $item->file_url }}" 
                                 alt="{{ $item->title }}" 
                                 class="img-fluid" 
                                 style="max-height: 80vh; width: auto;">
                        @else
                            <video src="{{ $item->file_url }}" 
                                   class="img-fluid" 
                                   style="max-height: 80vh; width: 100%;"
                                   controls autoplay></video>
                        @endif
                        @if($item->description)
                            <p class="mt-3 text-muted">{{ $item->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

@push('styles')
<style>
    .main-image {
        border-radius: 12px;
        overflow: hidden;
    }
    .side-media-item {
        transition: all 0.3s ease;
    }
    .side-media-item:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15) !important;
    }
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .description-box {
        border-left: 4px solid #3946B2;
    }
    .breadcrumb-item a {
        color: #3946B2;
    }
    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Fixer la colonne de droite */
    .col-lg-4 > div {
        position: sticky;
        top: 20px;
    }

    /* Style des albums similaires */
    .related-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    .related-item:hover {
        background-color: #f0f2f8 !important;
        transform: translateX(5px);
        border-left-color: #3946B2;
    }
    
    .related-albums {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e9ecef;
    }

    #more-media {
        transition: all 0.3s ease;
    }
</style>
@endpush
@endsection
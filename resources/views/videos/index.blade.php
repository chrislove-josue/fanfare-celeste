{{-- resources/views/videos/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Vidéos - Fanfare Trompette de Dieu')

@section('content')

<!-- Section Vidéos YouTube -->
<div class="videos-container">
    <!-- En-tête de la chaîne -->
    <div class="channel-header bg-white rounded-3 shadow-sm p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-1">
                    <i class="fab fa-youtube text-danger me-2"></i>Fanfare Trompette de Dieu
                </h3>
                <p class="text-muted mb-2">
                    <i class="fas fa-users me-1"></i> 145 abonnés
                    <span class="mx-2">•</span>
                    <i class="fas fa-video me-1"></i> {{ isset($videos) ? count($videos) : 0 }} vidéos
                </p>
                <a href="https://www.youtube.com/@fanfaretrompettededieu8359" target="_blank" class="btn btn-danger btn-sm">
                    <i class="fab fa-youtube me-2"></i> S'abonner sur YouTube
                </a>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="bg-light rounded p-3 d-inline-block">
                    <i class="fas fa-music fa-2x text-primary"></i>
                    <span class="ms-2 fw-bold">La Trompette de Dieu</span>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="position-absolute bottom-0 end-0 m-2">
                            <span class="badge bg-dark bg-opacity-75 px-2 py-1">
                                <i class="fas fa-eye me-1"></i> {{ $video['views'] }}
                            </span>
                        </div>
                    </div>
                    <div class="p-3">
                        <h6 class="card-title fw-bold text-dark" style="font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $video['title'] }}
                        </h6>
                        <small class="text-muted">
                            <i class="far fa-calendar-alt me-1"></i> {{ $video['date'] }}
                        </small>
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

    <div class="text-center mt-4">
        <a href="https://www.youtube.com/@fanfaretrompettededieu8359/videos" target="_blank" class="btn btn-outline-danger">
            <i class="fab fa-youtube me-2"></i> Voir toutes les vidéos sur YouTube
        </a>
    </div>
</div>

@push('styles')
<style>
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
</style>
@endpush
@endsection
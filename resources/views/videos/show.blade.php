{{-- resources/views/videos/show.blade.php --}}

@extends('layouts.app')

@section('title', ($currentVideo['title'] ?? 'Vidéo') . ' - Fanfare Trompette de Dieu')

@section('content')
<section class="video-player-section" style="background: #B8905C; min-height: 100vh; padding-top: 40px !important;">
    <div class="container">
        <!-- ✅ Bouton retour en haut -->
        <div class="mb-4">
            <a href="{{ route('public.gallery.index') }}" class="text-white text-decoration-none" style="font-size: 1.1rem;">
                <i class="fas fa-arrow-left me-2"></i> Retour à la galerie
            </a>
        </div>

        <div class="row g-4">
            <!-- Lecteur vidéo -->
            <div class="col-lg-8">
                <div class="video-wrapper bg-[#B8905C] rounded-3 overflow-hidden" style="position: relative; padding-bottom: 56.25%; height: 0;">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ $currentVideo['id'] ?? '' }}?autoplay=1&rel=0&modestbranding=1&showinfo=0&iv_load_policy=3" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                        frameborder="0" 
                        allow="autoplay; encrypted-media; fullscreen" 
                        allowfullscreen>
                    </iframe>
                </div>

                <!-- Informations de la vidéo -->
                <div class="video-info bg-white p-4 rounded-3 mt-3">
                    <h3 class="fw-bold mb-2">{{ $currentVideo['title'] ?? 'Vidéo' }}</h3>
                </div>

                <!-- Navigation vidéos suivantes/précédentes -->
                <div class="d-flex gap-3 mt-3">
                    @if(isset($prevVideo))
                        <a href="{{ route('videos.show', $prevVideo['id']) }}" class="btn btn-outline-light flex-grow-1" style="background: rgba(255,255,255,0.1);">
                            <i class="fas fa-chevron-left me-2"></i> Précédent
                        </a>
                    @endif
                    @if(isset($nextVideo))
                        <a href="{{ route('videos.show', $nextVideo['id']) }}" class="btn btn-danger flex-grow-1">
                            Suivant <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Playlist latérale -->
            <div class="col-lg-4">
                <div class="playlist-sidebar bg-white rounded-3 p-3" style="max-height: 600px; overflow-y: auto;">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-list me-2"></i> Liste des vidéos
                        <span class="badge bg-secondary ms-2">{{ count($videos ?? []) }}</span>
                    </h5>
                    
                    @if(isset($videos) && count($videos) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($videos as $video)
                        <a href="{{ route('videos.show', $video['id']) }}" 
                           class="list-group-item list-group-item-action d-flex gap-3 align-items-center {{ isset($currentVideo) && $video['id'] == $currentVideo['id'] ? 'active bg-danger text-white' : '' }}"
                           style="border: none; padding: 8px 0;">
                            <img src="{{ $video['thumbnail'] }}" 
                                 alt="{{ $video['title'] }}" 
                                 style="width: 120px; height: 68px; object-fit: cover; border-radius: 4px; flex-shrink: 0;">
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 0.8rem; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $video['title'] }}
                                </div>
                            </div>
                            @if(isset($currentVideo) && $video['id'] == $currentVideo['id'])
                                <i class="fas fa-play-circle text-white"></i>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-4">Aucune vidéo disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ✅ Espace supplémentaire en haut */
    .video-player-section {
        padding-top: 40px !important;
        padding-bottom: 10px;
    }
    
    /* Sur mobile, réduire l'espace */
    @media (max-width: 768px) {
        .video-player-section {
            padding-top: 40px !important;
            padding-bottom: 10px;
        }
    }
    
    .video-wrapper iframe {
        border: none;
    }
    
    .playlist-sidebar::-webkit-scrollbar {
        width: 5px;
    }
    .playlist-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .playlist-sidebar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .playlist-sidebar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .list-group-item.active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
    .list-group-item {
        transition: all 0.2s ease;
    }
    .list-group-item:hover:not(.active) {
        background-color: #f8f9fa;
    }
    
    /* Style du bouton retour */
    .video-player-section .text-white {
        transition: opacity 0.3s ease;
    }
    .video-player-section .text-white:hover {
        opacity: 0.8;
    }
    
    /* Cache le logo YouTube si possible */
    .ytp-watermark {
        display: none !important;
    }
</style>
@endpush
@endsection
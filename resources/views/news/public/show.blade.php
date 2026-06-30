@extends('layouts.app')

@section('title', $news->title)

@section('meta_description', $news->meta_description ?? Str::limit(strip_tags($news->content), 160))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.news.index') }}">Actualités</a></li>
                    <li class="breadcrumb-item active">{{ $news->title }}</li>
                </ol>
            </nav>

            <article>
                <h1 class="mb-3 display-6 fw-bold">{{ $news->title }}</h1>
                
                <div class="gap-3 mb-4 d-flex align-items-center text-muted">
                    <span>
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ $news->published_at ? $news->published_at->format('d/m/Y à H:i') : 'Date non définie' }}
                    </span>
                    @if(isset($news->views_count))
                        <span>
                            <i class="fas fa-eye me-1"></i>
                            {{ $news->views_count }} vues
                        </span>
                    @endif
                </div>

                @if($news->cover_image_path)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $news->cover_image_path) }}" 
                             class="rounded shadow-sm img-fluid" 
                             alt="{{ $news->title }}"
                             style="max-height: 400px; width: 100%; object-fit: cover;">
                    </div>
                @endif

                <div class="content fs-5 lh-lg">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </article>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('public.news.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux actualités
                </a>
                
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Actualités similaires -->
            @if(isset($relatedNews) && $relatedNews->count() > 0)
                <div class="shadow-sm card sticky-top" style="top: 2rem;">
                    <div class="text-white card-header bg-primary">
                        <h5 class="mb-0 card-title">
                            <i class="fas fa-newspaper me-2"></i>
                            Actualités similaires
                        </h5>
                    </div>
                    <div class="p-0 card-body">
                        @foreach($relatedNews as $related)
                            <div class="p-3 border-bottom">
                                <a href="{{ route('public.news.show', $related->slug) }}" 
                                   class="text-decoration-none">
                                    <h6 class="mb-1">{{ $related->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $related->published_at ? $related->published_at->format('d/m/Y') : 'Date non définie' }}
                                    </small>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Social Sharing -->
            <div class="mt-4 shadow-sm card">
                <div class="card-body">
                    <h6 class="mb-3 card-title">
                        <i class="fas fa-share-alt me-2"></i>
                        Partager cet article
                    </h6>
                    <div class="gap-2 d-flex">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" class="btn btn-outline-primary">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(url()->current()) }}" 
                           target="_blank" class="btn btn-outline-info">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}" 
                           target="_blank" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                           target="_blank" class="btn btn-outline-secondary">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2d2d2d;
}
.content p {
    margin-bottom: 1.5rem;
}
</style>
@endsection
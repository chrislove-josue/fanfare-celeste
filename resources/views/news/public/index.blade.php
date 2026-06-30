@extends('layouts.app')

@section('title', 'Actualités')

@section('content')
<div class="container py-5">
    <div class="mb-4 row">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold">
                <i class="fas fa-newspaper me-2 text-primary"></i>
                Actualités
            </h1>
            <p class="text-muted">Toute l'actualité de la Fanfare Céleste</p>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('public.news.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    @if($news->count() > 0)
        <div class="row g-4">
            @foreach($news as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="shadow-sm card h-100 hover-card">
                        @if($item->cover_image_path)
                            <img src="{{ asset('storage/' . $item->cover_image_path) }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->title }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-newspaper fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="mb-2 badge bg-primary">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $item->published_at ? $item->published_at->format('d/m/Y') : 'Date non définie' }}
                            </span>
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text text-muted">
                                {{ Str::limit(strip_tags($item->content), 120) }}
                            </p>
                        </div>
                        <div class="bg-transparent border-0 card-footer">
                            <a href="{{ route('public.news.show', $item->slug) }}" class="btn btn-outline-primary w-100">
                                Lire la suite <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $news->links() }}
        </div>
    @else
        <div class="py-5 text-center">
            <i class="mb-3 fas fa-newspaper fa-4x text-muted"></i>
            <h3>Aucune actualité disponible</h3>
            <p class="text-muted">Revenez plus tard pour découvrir nos actualités.</p>
        </div>
    @endif
</div>

<style>
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
}
</style>
@endsection
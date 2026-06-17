@extends('layouts.app')

@section('title', 'Galerie - Fanfare Céleste')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-1">Galerie</h1>
                <p class="text-muted mb-0">Découvrez nos photos et vidéos.</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($galleries as $gallery)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100" style="border-radius: 15px; overflow:hidden;">
                        @if($gallery->type === 'video')
                            <video src="{{ \Storage::url($gallery->file_path) }}" controls class="w-100" style="height: 260px; object-fit: cover;"></video>
                        @else
                            <img src="{{ \Storage::url($gallery->file_path) }}" class="w-100" alt="{{ $gallery->title }}" style="height: 260px; object-fit: cover;">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $gallery->title }}</h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Aucun contenu trouvé.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $galleries->links() }}
        </div>
    </div>
</section>
@endsection


@extends('layouts.admin')

@section('title', $news->title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-newspaper me-2 text-primary"></i>
            {{ $news->title }}
        </h1>
        <div>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
            <a href="{{ route('public.news.show', $news->slug) }}" target="_blank" class="btn btn-info">
                <i class="fas fa-eye me-2"></i> Voir sur le site
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($news->featured_image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $news->featured_image) }}" 
                         alt="{{ $news->title }}" 
                         class="img-fluid rounded" 
                         style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Statut :</strong>
                    @if($news->status === 'published')
                        <span class="badge bg-success">✅ Publié</span>
                    @else
                        <span class="badge bg-warning">📝 Brouillon</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <strong>Date de publication :</strong>
                    {{ $news->published_at ? $news->published_at->format('d/m/Y à H:i') : 'Non publié' }}
                </div>
            </div>

            @if($news->excerpt)
                <div class="alert alert-info">
                    <strong>Extrait :</strong>
                    {{ $news->excerpt }}
                </div>
            @endif

            <div class="content mt-4">
                {!! nl2br(e($news->content)) !!}
            </div>

            <hr>
            <div class="text-muted small">
                <div>Créé le : {{ $news->created_at->format('d/m/Y à H:i') }}</div>
                <div>Dernière modification : {{ $news->updated_at->format('d/m/Y à H:i') }}</div>
                <div>Slug : {{ $news->slug }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
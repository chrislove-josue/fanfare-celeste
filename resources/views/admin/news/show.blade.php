@extends('layouts.admin')

@section('title', $news->title)

@section('page-title', $news->title)
@section('page-subtitle', 'Détail de l\'actualité')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Actualités</a></li>
    <li class="breadcrumb-item active">{{ $news->title }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="shadow-sm card">
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'warning' }} fs-6">
                        {{ ucfirst($news->status) }}
                    </span>
                    @if($news->published_at)
                        <span class="text-muted ms-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Publié le : {{ $news->published_at->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>

                @if($news->excerpt)
                    <div class="alert alert-info">
                        <i class="fas fa-quote-left me-2"></i>
                        {{ $news->excerpt }}
                    </div>
                @endif

                <div class="mt-3 content">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Créé le : {{ $news->created_at->format('d/m/Y à H:i') }}
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-edit me-1"></i>
                        Modifié le : {{ $news->updated_at->format('d/m/Y à H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($news->cover_image_path)
            <div class="mb-4 shadow-sm card">
                <div class="card-header">
                    <h5 class="mb-0 card-title">
                        <i class="fas fa-image me-2 text-primary"></i>
                        Image à la une
                    </h5>
                </div>
                <div class="text-center card-body">
                    <img src="{{ asset('storage/' . $news->cover_image_path) }}" 
                         alt="{{ $news->title }}" 
                         class="rounded img-fluid" 
                         style="max-height: 300px; object-fit: cover;">
                </div>
            </div>
        @endif

        <div class="shadow-sm card">
            <div class="card-header">
                <h5 class="mb-0 card-title">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Informations
                </h5>
            </div>
            <div class="card-body">
                <dl class="mb-0 row">
                    <dt class="col-sm-5">ID</dt>
                    <dd class="col-sm-7">#{{ $news->id }}</dd>

                    <dt class="col-sm-5">Slug</dt>
                    <dd class="col-sm-7"><code>{{ $news->slug }}</code></dd>

                    <dt class="col-sm-5">Statut</dt>
                    <dd class="col-sm-7">
                        <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'warning' }}">
                            {{ ucfirst($news->status) }}
                        </span>
                    </dd>

                    @if($news->published_at)
                        <dt class="col-sm-5">Publié le</dt>
                        <dd class="col-sm-7">{{ $news->published_at->format('d/m/Y à H:i') }}</dd>
                    @endif

                    <dt class="col-sm-5">Créé le</dt>
                    <dd class="col-sm-7">{{ $news->created_at->format('d/m/Y à H:i') }}</dd>

                    <dt class="col-sm-5">Modifié le</dt>
                    <dd class="col-sm-7">{{ $news->updated_at->format('d/m/Y à H:i') }}</dd>
                </dl>
            </div>
            <div class="card-footer">
                <div class="gap-2 d-grid">
                    <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Modifier
                    </a>
                    <a href="{{ route('public.news.show', $news->slug) }}" target="_blank" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i> Voir sur le site
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
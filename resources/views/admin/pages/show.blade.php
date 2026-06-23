@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="page-header mb-4">
                <h1 class="display-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="lead text-muted">{{ $page->excerpt }}</p>
                @endif
            </div>
            
            @if($page->featured_image)
                <div class="featured-image mb-4">
                    <img src="{{ asset('storage/' . $page->featured_image) }}" 
                         alt="{{ $page->title }}" 
                         class="img-fluid rounded">
                </div>
            @endif
            
            <div class="page-content">
                {!! $page->content !!}
            </div>
            
            <div class="page-footer mt-4 text-muted small">
                <p>Dernière mise à jour : {{ $page->updated_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Créer une page')

@section('page-title', 'Créer une page')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Pages</a></li>
    <li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Contenu</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="15">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Page parente</label>
                                <select class="form-select" name="parent_id">
                                    <option value="">Aucune</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="template" class="form-label">Template</label>
                                <select class="form-select" name="template">
                                    <option value="default">Par défaut</option>
                                    <option value="full-width">Pleine largeur</option>
                                    <option value="landing">Page d'atterrissage</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="order" class="form-label">Ordre</label>
                                <input type="number" class="form-control" name="order" value="{{ old('order', 0) }}">
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_published" value="0">
                                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">Publié</label>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta titre</label>
                                <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta description</label>
                                <textarea class="form-control" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Créer la page
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
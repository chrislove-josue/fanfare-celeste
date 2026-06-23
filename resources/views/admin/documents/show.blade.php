@extends('layouts.admin')

@section('title', $document->title)

@section('page-title', $document->title)
@section('page-subtitle', 'Détails du document')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
    <li class="breadcrumb-item active">{{ $document->title }}</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            {{ $document->title }}
        </h1>
        <div>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-primary">
                <i class="fas fa-download me-2"></i> Télécharger
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <dl class="row">
                        <dt class="col-sm-3">Titre</dt>
                        <dd class="col-sm-9">{{ $document->title }}</dd>

                        <dt class="col-sm-3">Catégorie</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-info">
                                {{ \App\Models\Document::getCategories()[$document->category] ?? $document->category }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Description</dt>
                        <dd class="col-sm-9">{{ $document->description ?? '-' }}</dd>

                        <dt class="col-sm-3">Fichier</dt>
                        <dd class="col-sm-9">{{ $document->file_name }}</dd>

                        <dt class="col-sm-3">Taille</dt>
                        <dd class="col-sm-9">{{ $document->formatted_size }}</dd>

                        <dt class="col-sm-3">Type</dt>
                        <dd class="col-sm-9">{{ $document->file_type ?? '-' }}</dd>

                        <dt class="col-sm-3">Uploadé par</dt>
                        <dd class="col-sm-9">{{ $document->uploader->name ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Date du document</dt>
                        <dd class="col-sm-9">{{ $document->document_date ? $document->document_date->format('d/m/Y') : '-' }}</dd>

                        <dt class="col-sm-3">Visibilité</dt>
                        <dd class="col-sm-9">
                            @if($document->is_public)
                                <span class="badge bg-success">
                                    <i class="fas fa-globe me-1"></i> Public
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-lock me-1"></i> Privé
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Créé le</dt>
                        <dd class="col-sm-9">{{ $document->created_at->format('d/m/Y à H:i') }}</dd>

                        <dt class="col-sm-3">Modifié le</dt>
                        <dd class="col-sm-9">{{ $document->updated_at->format('d/m/Y à H:i') }}</dd>
                    </dl>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                            <p class="mb-0">
                                <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-primary">
                                    <i class="fas fa-download me-2"></i> Télécharger
                                </a>
                            </p>
                            <p class="text-muted small mt-2">
                                {{ $document->file_name }}<br>
                                ({{ $document->formatted_size }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
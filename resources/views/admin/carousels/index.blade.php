@extends('layouts.admin')

@section('title', 'Gestion du carousel')

@section('page-title', 'Carousel')
@section('page-subtitle', 'Gestion des slides du carousel')
@section('breadcrumb')
    <li class="breadcrumb-item active">Carousel</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-images me-2 text-primary"></i>
            Slides du carousel
        </h5>
        <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nouveau slide
        </a>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger m-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 150px;">Image</th>
                        <th>Titre</th>
                        <th>Sous-titre</th>
                        <th>Statut</th>
                        <th>Ordre</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carousels as $carousel)
                    <tr>
                        <td>
                            @if($carousel->image)
                                <img src="{{ asset('storage/' . $carousel->image) }}" 
                                     alt="{{ $carousel->title }}" 
                                     style="width: 120px; height: 70px; object-fit: cover; border-radius: 6px;">
                            @else
                                <div class="text-muted small">Aucune image</div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $carousel->title ?? 'Sans titre' }}</strong>
                            @if($carousel->link)
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-link"></i> {{ $carousel->link }}
                                </small>
                            @endif
                        </td>
                        <td>{{ $carousel->subtitle ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $carousel->is_active ? 'success' : 'secondary' }}">
                                {{ $carousel->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>{{ $carousel->order ?? 0 }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.carousels.edit', $carousel) }}" 
                                   class="btn btn-outline-secondary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.carousels.destroy', $carousel) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce slide ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-images fa-2x d-block mb-2"></i>
                            Aucun slide dans le carousel
                            <br>
                            <a href="{{ route('admin.carousels.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Ajouter un slide
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
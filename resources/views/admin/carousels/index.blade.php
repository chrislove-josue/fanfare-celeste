@extends('layouts.admin')

@section('title', 'Gestion du carousel')

@section('page-title', 'Carousel')
@section('page-subtitle', 'Gestion des slides du carousel')
@section('breadcrumb')
    <li class="breadcrumb-item active">Carousel</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h3 mb-0">
            <i class="fas fa-sliders-h me-2 text-primary"></i>
            Gestion du carousel
        </h1>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Ajouter un slide
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2 text-white">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total slides</div>
                        <div class="h5 mb-0">{{ $carousels->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle p-2 text-white">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Actifs</div>
                        <div class="h5 mb-0">{{ $carousels->where('is_active', true)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-secondary rounded-circle p-2 text-white">
                            <i class="fas fa-eye-slash"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Inactifs</div>
                        <div class="h5 mb-0">{{ $carousels->where('is_active', false)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des slides -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 150px;">Image</th>
                            <th>Titre</th>
                            <th>Sous-titre</th>
                            <th>Lien</th>
                            <th>Statut</th>
                            <th>Ordre</th>
                            <th style="width: 200px;">Actions</th>
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
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 120px; height: 70px;">
                                        <i class="fas fa-image text-muted fa-2x"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $carousel->title ?? 'Sans titre' }}</strong>
                            </td>
                            <td>{{ $carousel->subtitle ?? '-' }}</td>
                            <td>
                                @if($carousel->link)
                                    <a href="{{ $carousel->link }}" target="_blank" class="text-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($carousel->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>{{ $carousel->order ?? 0 }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.carousels.edit', $carousel) }}" class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.carousels.toggle', $carousel) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $carousel->is_active ? 'secondary' : 'success' }}" 
                                                title="{{ $carousel->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $carousel->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.carousels.destroy', $carousel) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce slide ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-sliders-h fa-2x d-block mb-2 text-muted"></i>
                                <p class="text-muted">Aucun slide dans le carousel</p>
                                <a href="{{ route('admin.carousels.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i> Ajouter un slide
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
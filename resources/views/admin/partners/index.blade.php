@extends('layouts.admin')

@section('title', 'Gestion des partenaires')

@section('page-title', 'Partenaires')
@section('page-subtitle', 'Gestion des partenaires de la fanfare')
@section('breadcrumb')
    <li class="breadcrumb-item active">Partenaires</li>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h1 class="h3 mb-0">
            <i class="fas fa-handshake me-2 text-primary"></i>
            Gestion des partenaires
        </h1>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Ajouter un partenaire
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

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle p-2 text-white">
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total partenaires</div>
                        <div class="h5 mb-0">{{ $totalPartners }}</div>
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
                        <div class="h5 mb-0">{{ $activePartners }}</div>
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
                        <div class="h5 mb-0">{{ $inactivePartners }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.partners.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recherche</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nom, description...">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="is_active" class="form-select">
                        <option value="">Tous</option>
                        <option value="yes" {{ request('is_active') == 'yes' ? 'selected' : '' }}>Actif</option>
                        <option value="no" {{ request('is_active') == 'no' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary" title="Réinitialiser">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des partenaires -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Logo</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Description</th>
                            <th>Site web</th>
                            <th>Statut</th>
                            <th>Ordre</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $partner)
                        <tr>
                            <td>
                                @if($partner->logo_url)
                                    <img src="{{ $partner->logo_url }}" 
                                         alt="{{ $partner->name }}" 
                                         style="width: 50px; height: 50px; object-fit: contain;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-building text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $partner->name }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $categories[$partner->category] ?? $partner->category }}</span>
                            </td>
                            <td>
                                @if($partner->description)
                                    {{ Str::limit($partner->description, 40) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($partner->website)
                                    <a href="{{ $partner->website }}" target="_blank" class="text-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($partner->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>{{ $partner->order ?? 0 }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.partners.toggle-active', $partner) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $partner->is_active ? 'secondary' : 'success' }}" 
                                                title="{{ $partner->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $partner->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce partenaire ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-handshake fa-2x d-block mb-2 text-muted"></i>
                                <p class="text-muted">Aucun partenaire trouvé</p>
                                <a href="{{ route('admin.partners.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-2"></i> Ajouter un partenaire
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small">
                        Affichage de {{ $partners->firstItem() ?? 0 }} à {{ $partners->lastItem() ?? 0 }} sur {{ $partners->total() }} partenaires
                    </span>
                </div>
                <div>
                    {{ $partners->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
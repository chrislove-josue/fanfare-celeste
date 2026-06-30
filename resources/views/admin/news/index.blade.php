@extends('layouts.admin')

@section('title', 'Gestion des actualités & événements')

@section('page-title', 'Actualités & Événements')
@section('page-subtitle', 'Gestion des articles, actualités et événements')
@section('breadcrumb')
    <li class="breadcrumb-item active">Actualités & Événements</li>
@endsection

@section('content')
<div class="shadow-sm card">
    <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">
            <i class="fas fa-newspaper me-2 text-primary"></i>
            Liste des actualités & événements
        </h5>
        <div class="gap-2 d-flex">
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Nouvelle actualité
            </a>
            <a href="{{ route('admin.events.create') }}" class="btn btn-success">
                <i class="fas fa-calendar-plus me-2"></i> Nouvel événement
            </a>
        </div>
    </div>
    <div class="p-0 card-body">
        @if(session('success'))
            <div class="m-3 alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="m-3 alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="m-3 alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        <!-- Filtres -->
        <div class="p-3 border-bottom">
            <form method="GET" action="{{ route('admin.news.index') }}" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">Tous les types</option>
                        <option value="news" {{ request('type') === 'news' ? 'selected' : '' }}>Actualités</option>
                        <option value="event" {{ request('type') === 'event' ? 'selected' : '' }}>Événements</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>À venir</option>
                        <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>En cours</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table mb-0 table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Extrait / Description</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>
                            @if($item->type === 'event')
                                @if($item->poster_path)
                                    <img src="{{ asset('storage/' . $item->poster_path) }}" 
                                         alt="{{ $item->title }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div class="rounded bg-primary d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; color: #fff;">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                @endif
                            @else
                                @if($item->cover_image_path)
                                    <img src="{{ asset('storage/' . $item->cover_image_path) }}" 
                                         alt="{{ $item->title }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; color: #6c757d;">
                                        <i class="fas fa-newspaper fa-2x"></i>
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item->title }}</strong>
                            <br>
                            <small class="text-muted">
                                @if($item->type === 'event')
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                    {{ $item->start_date ? $item->start_date->format('d/m/Y H:i') : 'Date non définie' }}
                                @else
                                    Slug: {{ $item->slug }}
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($item->type === 'event')
                                <span class="badge bg-primary">Événement</span>
                            @else
                                <span class="badge bg-info">Actualité</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;">
                                @if($item->type === 'event')
                                    {{ Str::limit(strip_tags($item->description ?? ''), 80) }}
                                @else
                                    {{ Str::limit(strip_tags($item->content ?? ''), 80) }}
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($item->type === 'event')
                                @if($item->status === 'upcoming')
                                    <span class="badge bg-primary">À venir</span>
                                @elseif($item->status === 'ongoing')
                                    <span class="badge bg-success">En cours</span>
                                @elseif($item->status === 'completed')
                                    <span class="badge bg-secondary">Terminé</span>
                                @elseif($item->status === 'cancelled')
                                    <span class="badge bg-danger">Annulé</span>
                                @else
                                    <span class="badge bg-warning">{{ $item->status }}</span>
                                @endif
                            @else
                                @if($item->status === 'published')
                                    <span class="badge bg-success">Publié</span>
                                @elseif($item->status === 'draft')
                                    <span class="badge bg-warning">Brouillon</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($item->type === 'event')
                                {{ $item->start_date ? $item->start_date->format('d/m/Y') : '-' }}
                            @else
                                {{ $item->published_at ? $item->published_at->format('d/m/Y') : '-' }}
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if($item->type === 'event')
                                    <a href="{{ route('admin.news.show-event', $item) }}" 
                                       class="btn btn-outline-primary" title="Voir le détail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('public.events.show', $item) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-info" title="Voir sur le site">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.news.edit-event', $item) }}" 
                                       class="btn btn-outline-secondary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy-event', $item) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cet événement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.news.show', $item) }}" 
                                       class="btn btn-outline-primary" title="Voir le détail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('public.news.show', $item->slug) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-info" title="Voir sur le site">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('admin.news.edit', $item) }}" 
                                       class="btn btn-outline-secondary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette actualité ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-muted">
                            <i class="mb-2 fas fa-newspaper fa-2x d-block"></i>
                            Aucune actualité ou événement trouvé
                            <br>
                            <div class="mt-2">
                                <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter une actualité
                                </a>
                                <a href="{{ route('admin.events.create') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-calendar-plus"></i> Ajouter un événement
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($items) && method_exists($items, 'links'))
            <div class="p-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Statistiques rapides -->
<div class="mt-4 row">
    <div class="col-md-3">
        <div class="shadow-sm card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="p-2 text-white bg-primary rounded-circle">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total actualités</div>
                    <div class="mb-0 h5">{{ $totalNews ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="shadow-sm card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="p-2 text-white bg-success rounded-circle">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Total événements</div>
                    <div class="mb-0 h5">{{ $totalEvents ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="shadow-sm card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="p-2 text-white bg-warning rounded-circle">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Événements à venir</div>
                    <div class="mb-0 h5">{{ $upcomingEvents ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="shadow-sm card">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <div class="p-2 text-white bg-info rounded-circle">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div>
                    <div class="text-muted small">Publiés / Actifs</div>
                    <div class="mb-0 h5">{{ $publishedCount ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
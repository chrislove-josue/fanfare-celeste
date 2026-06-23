@extends('layouts.admin')

@section('title', 'Gestion des témoignages')

@section('page-title', 'Témoignages')
@section('page-subtitle', 'Gestion des témoignages des membres')
@section('breadcrumb')
    <li class="breadcrumb-item active">Témoignages</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-quote-right me-2 text-primary"></i>
            Liste des témoignages
        </h5>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nouveau témoignage
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
                        <th style="width: 60px;">Photo</th>
                        <th>Auteur</th>
                        <th>Rôle</th>
                        <th>Contenu</th>
                        <th>Note</th>
                        <th>Statut</th>
                        <th>Ordre</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                    <tr>
                        <td>
                            @if($testimonial->author_image)
                                <img src="{{ asset('storage/' . $testimonial->author_image) }}" 
                                     alt="{{ $testimonial->author_name }}" 
                                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; font-size: 16px; font-weight: 600;">
                                    {{ strtoupper(substr($testimonial->author_name, 0, 2)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $testimonial->author_name }}</strong>
                        </td>
                        <td>{{ $testimonial->author_role ?? '-' }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px;">
                                {{ Str::limit($testimonial->content, 60) }}
                            </div>
                        </td>
                        <td>
                            @if($testimonial->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}" 
                                       style="font-size: 12px;"></i>
                                @endfor
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $testimonial->is_active ? 'success' : 'secondary' }}">
                                {{ $testimonial->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            @if(!$testimonial->is_approved)
                                <span class="badge bg-warning">En attente</span>
                            @endif
                        </td>
                        <td>{{ $testimonial->order ?? 0 }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @if(!$testimonial->is_approved)
                                    <form action="{{ route('admin.testimonials.approve', $testimonial) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Approuver">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                                   class="btn btn-outline-secondary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce témoignage ?')">
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
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-quote-right fa-2x d-block mb-2"></i>
                            Aucun témoignage
                            <br>
                            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Ajouter un témoignage
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
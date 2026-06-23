@extends('layouts.admin')

@section('title', 'Gestion du carousel')

@section('page-title', 'Carousel')
@section('breadcrumb')
    <li class="breadcrumb-item active">Carousel</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Slides du carousel</h5>
        <a href="{{ route('admin.carousels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau slide
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carousels as $carousel)
                    <tr>
                        <td>
                            @if($carousel->image)
                                <img src="{{ asset('storage/' . $carousel->image) }}" 
                                     alt="{{ $carousel->title }}" 
                                     style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                            @endif
                        </td>
                        <td>
                            <strong>{{ $carousel->title }}</strong>
                            @if($carousel->subtitle)
                                <br><small class="text-muted">{{ $carousel->subtitle }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $carousel->is_active ? 'success' : 'secondary' }}">
                                {{ $carousel->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>{{ $carousel->order }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.carousels.edit', $carousel) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.carousels.destroy', $carousel) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun slide dans le carousel</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
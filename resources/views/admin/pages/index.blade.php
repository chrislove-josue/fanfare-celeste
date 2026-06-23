@extends('layouts.admin')

@section('title', 'Gestion des pages')

@section('page-title', 'Pages')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pages</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Liste des pages</h5>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle page
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Statut</th>
                        <th>Template</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>
                            {{ $page->title }}
                            @if($page->parent)
                                <span class="badge bg-secondary">Sous-page</span>
                            @endif
                        </td>
                        <td><code>{{ $page->slug }}</code></td>
                        <td>
                            <span class="badge bg-{{ $page->is_published ? 'success' : 'warning' }}">
                                {{ $page->is_published ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td>{{ $page->template }}</td>
                        <td>{{ $page->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center text-muted">Aucune page créée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
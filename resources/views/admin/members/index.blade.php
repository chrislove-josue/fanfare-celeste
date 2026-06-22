@extends('layouts.app')

@section('title', 'Gestion des membres')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des membres</h1>
        <div>
            <a href="{{ route('admin.members.export') }}" class="btn btn-secondary me-2">
                <i class="fas fa-download"></i> Exporter PDF
            </a>
            <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau membre
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.members.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nom, prénom, email...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Section</label>
                    <select name="section" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>
                                {{ $section }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Instrument</label>
                    <select name="instrument" class="form-select">
                        <option value="">Tous</option>
                        @foreach($instruments as $instrument)
                            <option value="{{ $instrument }}" {{ request('instrument') == $instrument ? 'selected' : '' }}>
                                {{ $instrument }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Membre</th>
                            <th>Photo</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Instrument</th>
                            <th>Section</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        <tr>
                            <td>{{ $member->member_number }}</td>
                            <td>
                                <img src="{{ $member->photo_url }}" alt="Photo" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            </td>
                            <td>{{ $member->full_name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>{{ $member->instrument ?? '-' }}</td>
                            <td>{{ $member->section ?? '-' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'suspended' => 'danger',
                                        'withdrawn' => 'secondary',
                                        'deceased' => 'dark',
                                        'training' => 'info'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$member->status] ?? 'secondary' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.members.show', $member) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.members.card', $member) }}" class="btn btn-primary">
                                        <i class="fas fa-id-card"></i>
                                    </a>

                                    @if($member->status === 'pending')
                                        <form action="{{ route('admin.members.approve', $member) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Approuver ce membre ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Suppression définitive --}}
                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer définitivement ce membre ? Cette action est irréversible.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Aucun membre trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $members->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


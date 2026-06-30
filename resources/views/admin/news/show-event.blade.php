@extends('layouts.admin')

@section('title', $event->title)

@section('page-title', $event->title)
@section('page-subtitle', 'Détail de l\'événement')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Actualités & Événements</a></li>
    <li class="breadcrumb-item active">{{ $event->title }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="mb-4 shadow-sm card">
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-{{ $event->status === 'upcoming' ? 'primary' : ($event->status === 'ongoing' ? 'success' : ($event->status === 'completed' ? 'secondary' : 'danger')) }} fs-6">
                        {{ ucfirst($event->status) }}
                    </span>
                    <span class="badge bg-info fs-6">{{ ucfirst($event->type) }}</span>
                    @if($event->start_date)
                        <span class="text-muted ms-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $event->start_date->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <strong>Lieu :</strong> {{ $event->location }}
                    @if($event->location_url)
                        <a href="{{ $event->location_url }}" target="_blank" class="ms-2">
                            <i class="fas fa-external-link-alt"></i> Voir sur la carte
                        </a>
                    @endif
                </div>

                <div class="mt-3 content">
                    {!! nl2br(e($event->description)) !!}
                </div>

                @if($event->notes)
                    <div class="mt-3">
                        <h6>Notes :</h6>
                        <div class="alert alert-secondary">
                            {{ $event->notes }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Créé le : {{ $event->created_at->format('d/m/Y à H:i') }}
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-edit me-1"></i>
                        Modifié le : {{ $event->updated_at->format('d/m/Y à H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Participants -->
        <div class="shadow-sm card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title">
                    <i class="fas fa-users me-2 text-primary"></i>
                    Participants ({{ $participants->count() }})
                </h5>
                @if($availableMembers->count() > 0)
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                        <i class="fas fa-user-plus me-1"></i> Ajouter
                    </button>
                @endif
            </div>
            <div class="p-0 card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Membre</th>
                                <th>Statut</th>
                                <th>Arrivée</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($participants as $participation)
                            <tr>
                                <td>
                                    <strong>{{ $participation->member->full_name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $participation->member->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $participation->attendance_status === 'present' ? 'success' : ($participation->attendance_status === 'absent' ? 'danger' : ($participation->attendance_status === 'late' ? 'warning' : 'info')) }}">
                                        {{ ucfirst($participation->attendance_status) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $participation->arrival_time ? $participation->arrival_time->format('H:i') : '-' }}
                                </td>
                                <td>{{ $participation->notes ?? '-' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateStatusModal{{ $participation->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.news.remove-participant', ['event' => $event, 'participation' => $participation]) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Retirer ce participant ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-3 text-center text-muted">
                                    Aucun participant inscrit
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($event->poster_path)
            <div class="mb-4 shadow-sm card">
                <div class="card-header">
                    <h5 class="mb-0 card-title">
                        <i class="fas fa-image me-2 text-primary"></i>
                        Affiche
                    </h5>
                </div>
                <div class="text-center card-body">
                    <img src="{{ asset('storage/' . $event->poster_path) }}" 
                         alt="{{ $event->title }}" 
                         class="rounded img-fluid" 
                         style="max-height: 300px; object-fit: cover;">
                </div>
            </div>
        @endif

        <div class="shadow-sm card">
            <div class="card-header">
                <h5 class="mb-0 card-title">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Informations
                </h5>
            </div>
            <div class="card-body">
                <dl class="mb-0 row">
                    <dt class="col-sm-5">ID</dt>
                    <dd class="col-sm-7">#{{ $event->id }}</dd>

                    <dt class="col-sm-5">Type</dt>
                    <dd class="col-sm-7">{{ ucfirst($event->type) }}</dd>

                    <dt class="col-sm-5">Statut</dt>
                    <dd class="col-sm-7">
                        <span class="badge bg-{{ $event->status === 'upcoming' ? 'primary' : ($event->status === 'ongoing' ? 'success' : ($event->status === 'completed' ? 'secondary' : 'danger')) }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </dd>

                    <dt class="col-sm-5">Début</dt>
                    <dd class="col-sm-7">{{ $event->start_date ? $event->start_date->format('d/m/Y H:i') : '-' }}</dd>

                    <dt class="col-sm-5">Fin</dt>
                    <dd class="col-sm-7">{{ $event->end_date ? $event->end_date->format('d/m/Y H:i') : '-' }}</dd>

                    <dt class="col-sm-5">Lieu</dt>
                    <dd class="col-sm-7">{{ $event->location }}</dd>

                    @if($event->responsible)
                        <dt class="col-sm-5">Responsable</dt>
                        <dd class="col-sm-7">{{ $event->responsible->name }}</dd>
                    @endif

                    @if($event->budget)
                        <dt class="col-sm-5">Budget</dt>
                        <dd class="col-sm-7">{{ number_format($event->budget, 0, ',', ' ') }} FCFA</dd>
                    @endif

                    @if($event->actual_cost)
                        <dt class="col-sm-5">Coût réel</dt>
                        <dd class="col-sm-7">{{ number_format($event->actual_cost, 0, ',', ' ') }} FCFA</dd>
                    @endif

                    <dt class="col-sm-5">Participants</dt>
                    <dd class="col-sm-7">{{ $participants->count() }}</dd>
                </dl>
            </div>
            <div class="card-footer">
                <div class="gap-2 d-grid">
                    <a href="{{ route('admin.news.edit-event', $event) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Modifier
                    </a>
                    <a href="{{ route('public.events.show', $event) }}" target="_blank" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i> Voir sur le site
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Participant -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.news.add-participant', $event) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2 text-success"></i>
                        Ajouter un participant
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Membre</label>
                        <select name="member_id" class="form-select" required>
                            <option value="">Choisir un membre</option>
                            @foreach($availableMembers as $member)
                                <option value="{{ $member->id }}">
                                    {{ $member->full_name }} ({{ $member->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals Mise à jour statut -->
@foreach($participants as $participation)
<div class="modal fade" id="updateStatusModal{{ $participation->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.news.update-participation', ['event' => $event, 'participation' => $participation]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Mettre à jour le statut
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Membre</label>
                        <p class="fw-bold">{{ $participation->member->full_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut de présence</label>
                        <select name="attendance_status" class="form-select" required>
                            <option value="confirmed" {{ $participation->attendance_status === 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="present" {{ $participation->attendance_status === 'present' ? 'selected' : '' }}>Présent</option>
                            <option value="absent" {{ $participation->attendance_status === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ $participation->attendance_status === 'late' ? 'selected' : '' }}>En retard</option>
                            <option value="excused" {{ $participation->attendance_status === 'excused' ? 'selected' : '' }}>Excusé</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $participation->notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection